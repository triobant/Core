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

use Horde\Test\TestCase;

use Horde;
use Horde_Registry;
use Horde_Exception;

class AppFinderTest extends TestCase
{
    use SetUpTrait;

    protected function getMiddleware()
    {
        $this->registry = $this->createMock(Horde_Registry::class);
        return new AppFinder(
            $this->registry
        );
    }
    /**
     * This tests if the Appfinder throws an exception if no app was found in path
     */
    public function testNoValidAppInPath()
    {
        $appname = 'foobar';
        $app = new Horde_Registry($this->registry->method('listApps')->willReturn($appname));
        // $urlone = 'https://example.ex/foobar/lws';
        $urltwo = 'https://bla.xy/foobar/qwe';
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($urltwo, 'No App found for this path');
    }
    /**
     * This tests 
     */
   /* public function testAppFound() 
    {
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals();
    }*/
}
