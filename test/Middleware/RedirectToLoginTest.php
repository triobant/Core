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

    public function testIsRedirectedToLogin()
    {
        //$username = 'testuser01';
        $middleware = $this->getMiddleware();
        $this->registry->method('isAuthenticated')->willReturn(false);
        //$this->registry->method('getAuth')->willReturn($username);
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);
        //$authUser = $this->recentlyHandledRequest->getAttribute('HORDE_AUTHENTICATED_USER');
        $this->recentlyHandledRequest->getAttribute('HORDE_AUTHENTICATED_USER');
        $redirect = $this->registry->getInitialPage('horde');
        //var_dump($redirect);
        //$this->redirect->method('Location')->willReturn($authUser);


        //$this->assertEquals($redirect, $authUser);
        $this->assertEquals(302, $response->getStatusCode());
    }

    /*public function testIsNotRedirectedToLogin()
    {
        $username = 'testuser01';
        $middleware = $this->getMiddleware();
        $this->registry->method('isAuthenticated')->willReturn(true);
        $this->registry->method('getAuth')->willReturn($username);
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $response = $middleware->process($request, $this->handler);
        $authUser = $this->recentlyHandledRequest->getAttribute('HORDE_AUTHENTICATED_USER');

        $this->assertEquals($username, $authUser);
        $this->assertEquals(302, $response->getStatusCode());
    }*/
}