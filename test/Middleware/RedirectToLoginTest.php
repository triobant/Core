<?php
/**
 * Copyright 2016-2021 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Core
 */

namespace Horde\Core\Test\Middleware;

use Horde\Core\Middleware\RedirectToLogin;

use Horde\Test\TestCase;

use Horde;
use Horde_Session;
use Horde_Exception;
use Horde_Registry;

class RedirectToLoginTest extends TestCase
{
    use SetUpTrait;

    protected function getMiddleware()
    {
        return new RedirectToLogin(
            $this->registry,
            $this->responseFactory);
    }

    /*public function testIsRedirectedToLogin()
    {
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);
        $redirect = (string)Horde::Url($this->registry->getInitialPage('horde'), true);
        $this->responseFactory->createResponse(302)->withHeader('Location', $redirect);
        //teste ob der header 'location' da ist und ob er den gleichen Wert hat wie $redirect
        //var_dump($redirect);
        //$this->redirect->method('Location')->willReturn($authUser);

        $this->assertEquals('Location', $redirect);
        $this->assertEquals(302, $response->getStatusCode());
    }*/

    public function testIsNotRedirectedToLogin()
    {
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $request = $request->withAttribute('HORDE_AUTHENTICATED_USER', true);
        $response = $middleware->process($request, $this->handler);
        //teste ob response code 200 ist

        $this->assertEquals(200, $response->getStatusCode());
    }
}