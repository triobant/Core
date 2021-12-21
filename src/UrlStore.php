<?php

declare(strict_types=1);

namespace Horde\Core;

use Horde;
use Horde_Url;

class UrlStore
{
    public function getInitialPage(string $app, bool $full): Horde_Url
    {
        return Horde::Url($this->registry->getInitialPage($app), $full);
    }
}
