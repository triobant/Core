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

use Horde\Core\Middleware\AppFinder;

use Psr\Http\Message\ServerRequestInterface; // added this for research purpose

use Horde\Test\TestCase;

use Horde;
use Horde\Http\Test\ServerRequestTest;
use Horde_Registry;
use Horde_Exception;
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
     * This tests if the Appfinder throws an exception if no app was found in path
     */

    public function testAppFound() /* This is just there for trial and error and to find a possible solution*/
    {
        // mock Horde_Registry, Horde_Registry::listApps, Horde_Registry::get

        
        $url = 'https://example.ex/foobar';
        $registry = $this->createMock(Horde_Registry::class);
        $request = $this->requestFactory->createServerRequest('GET', $url); // /'test' was changed to $url
        $request = $request->withAttribute('registry', $registry);

        
        $registry->method('listApps')->willReturn(['foobar']);
        $registry->method('get')->willReturn($url);
        
        
        $middleware = $this->getMiddleware();
        $response = $middleware->process($request, $this->handler);
        
        $found = $this->recentlyHandledRequest->getAttribute('app');
        
        $this->assertSame('foobar', $found);
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /*public function testNoValidAppInPath()
    {  
     
    }*/
    /**
     * This tests 
     */
}
