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
     * This tests if the Appfinder finds a valid app in path
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
        /*$url = 'https://example.ex/foobar';
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $url); // /'test' was changed to $url
        $request = $request->withAttribute('registry', $registry);

        
        $registry->method('listApps')->willReturn(['foobar']);
        $registry->method('get')->willReturn($url);
        
        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);
        
        $found = $this->recentlyHandledRequest->getAttribute('app');
        
        $this->assertSame('foobar', $found);
        $this->assertEquals(200, $response->getStatusCode());*/
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
        //$this->expectExceptionMessage("Yes");
        //$this->expectExceptionMessage("No");
        //$this->expectExceptionMessage("No App found for this path");
        $this->expectException(\Exception::class);
        $response = $middleware->process($request, $this->handler);
    }
    /**
     * This tests if the longest match path is the right app
     */

}
