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

class AppFinderTest extends TestCase
{
    use SetUpTrait;

    protected function getMiddleware()
    {
        return new AppFinder(
            $this->registry,
            $this->handler);
    }

    public function testNoAppFound()
    {
        $middleware = $this->getMiddleware();
     
        $this->assertEmpty($found);
    }

    public function testAppFound() 
    {
        $middleware = $this->getMiddleware();
    }
}
