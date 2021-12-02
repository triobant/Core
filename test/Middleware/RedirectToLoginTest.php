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

    public function IsRedirectedToLogin()
    {

    }

    public function IsNotRedirectedToLogin()
    {

    }
}