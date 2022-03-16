<?php
/**
 * Copyright 2016-2022 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Core
 */

namespace Horde\Core\Test\Middleware;

use Exception;
use Horde\Core\Middleware\AppFinder;

use Horde\Test\TestCase;

use Horde;
use Horde\Http\Test\ServerRequestTest;
use Horde_Registry;
use Horde_Exception;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\Null_;
use TypeError;

class AppFinderTest extends TestCase
{
    use SetUpTrait;

    protected function getMiddleware()
    {
        return new AppFinder(
            $this->registry
        );
    }
    /**
     * This tests if the AppFinder finds a valid app in path
     */
    public function testAppFound()
    {
        $baseUrl = 'https://example.ex/';
        $app = 'bar';
        $list = ['foobar', 'bla', 'foo', 'barfoo', 'bar'];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);


        $registry->method('listApps')->willReturn($list);
        $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });

        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);

        $foundApp = $this->recentlyHandledRequest->getAttribute('app');

        $this->assertSame($app, $foundApp);
    }
    /**
     * This tests if the Appfinder throws an exception if no app was found in path
     */
    public function testNoValidAppInPath()
    {
        $baseUrl = 'https://example.ex/';
        $app = 'amount';
        $list = ['foobar', 'bla', 'foo', 'barfoo', 'bar'];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);
        
        $registry->method('listApps')->willReturn($list);
        $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });
        
        
        $middleware = $this->getMiddleware();
        $this->expectExceptionMessage("No App found for this path");
        $this->expectException(\Exception::class);
        $response = $middleware->process($request, $this->handler);
    }
    /**
     * This tests if the longest match path is the right app
     */
    public function testLongestMatchPath() 
    {
        $baseUrl = 'https://example.ex/';
        $app = 'foobar';
        $list = ['foobar', 'foo'];
        $rlist = ['foo', 'foobar'];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);


        $registry->method('listApps')->willReturn($rlist);
        $registry->method('listApps')->willReturn($list);  
        $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });

        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);

        $foundApp = $this->recentlyHandledRequest->getAttribute('app');

        $this->assertSame($app, $foundApp);
    }
    /** 
     * This tests the case when there are NO available apps 
    */
    public function testNoAppAvailable() 
    {
        $baseUrl = 'https://example.ex/';
        $app = 'amount';
        $list = [];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);
        
        $registry->method('listApps')->willReturn($list);
        $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });
        
        
        $middleware = $this->getMiddleware();
        $this->expectExceptionMessage("No App found for this path");
        $this->expectException(\Exception::class);
        $response = $middleware->process($request, $this->handler);
    }
    /**
     * This tests if the routerprefix attribute is set properly
     */
    public function testRouterPrefixAttribute()
    {
        $baseUrl = 'https://example.ex/';
        $app = 'barfoo';
        $list = ['foobar', 'bla', 'foo', 'barfoo', 'bar'];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);


        $registry->method('listApps')->willReturn($list);
        $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });

        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);

        $routerPrefix = $this->recentlyHandledRequest->getAttribute('routerPrefix');
        
        $this->assertSame('/bar', $routerPrefix);
    }
    /**
     * This tests if the path gets normalized when it contains any . and .. levels
     */
    public function testNormalizePath() // change code accordingly so it returns the right and normalized path
    {
        $baseUrl = 'https://example.ex/./../';
        $app = 'bla';
        $list = ['foobar', 'bla', 'foo', 'barfoo', 'bar'];
        $requestUrl = $baseUrl . $app;
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $requestUrl);
        $request = $request->withAttribute('registry', $registry);


        $registry->method('listApps')->willReturn($list);
        $normalizePath = $registry->method('get')->willReturnCallback(function ($type, $app) use ($baseUrl) {
            return $baseUrl . $app;
        });
        
        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);

        $normalizePath = $this->recentlyHandledRequest->getAttribute('app');
        
        $this->assertSame($app, $normalizePath);
    }
    /**
     * This tests if the AppFinder will return the exception when the scheme or host are different
     */
    public function testSchemeHostDiff()
    {
        
    }
}
