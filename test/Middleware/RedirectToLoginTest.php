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
use Horde\Core\UrlStore;
use Horde\Test\TestCase;
use Horde_Url;

class RedirectToLoginTest extends TestCase
{
    use SetUpTrait;

    protected function getMiddleware()
    {
        $this->urlStore = $this->createMock(UrlStore::class);
        return new RedirectToLogin(
            $this->registry,
            $this->responseFactory,
            $this->urlStore
        );
    }

    public function testIsRedirectedToLogin()
    {
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');

        $url = new Horde_Url('/testpath');
        $this->urlStore->method('getInitialPage')->willReturn($url);
        $response = $middleware->process($request, $this->handler);

        $locationHeader = $response->getHeaderLine('Location');
        $this->assertEquals($locationHeader, (string) $url);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testIsNotRedirectedToLogin()
    {
        $middleware = $this->getMiddleware();
        $request = $this->requestFactory->createServerRequest('GET', '/test');
        $request = $request->withAttribute('HORDE_AUTHENTICATED_USER', true);
        $response = $middleware->process($request, $this->handler);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
