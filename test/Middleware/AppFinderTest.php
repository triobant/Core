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

    public function testTest() /* This is just there for trial and error and to find a possible solution*/
    {
        $app = 'https://example.ex/foobar/lws';
        $appname = $this->registry->method('listApps')->willReturn('foobar');
        $id = $this->createMock(Horde_Registry::class);
        $found = $this->registry->method('identifyApp')->willReturn($id, $appname);
        $this->registry->method('get')->willReturn('webroot', $app);
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($found, $app);
    }
    
    /*public function testNoValidAppInPath()
    {
        $appname = 'foobar'; // variable with the name of the app
        $app = new Horde_Registry($this->registry->method('listApps')->willReturn($appname)); // Trying to mock the method that will return the name of the app in the lists
        // $urlone = 'https://example.ex/foobar/lws';
        $urltwo = 'https://bla.xy/foobar/qwe'; // Mock for an example url
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);
        $notfound = $this->registry->method('identifyApp')->willReturn($urltwo, $app); // I want to see if $appname was found within the url
        $message = new \Exception('No App found for this path'); // Need to set the exception message if app wasn't found

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($notfound, $message);
    }*/
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
