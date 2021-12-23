<?php

declare(strict_types=1);

namespace Horde\Core;

use Horde_Url;
use Horde;
Use Horde_Registry;

class UrlStore 
{
    public function getInitialPage(string $app, bool $full): Horde_Url
    {
        return Horde::Url($this->registry->getInitialPage($app, $full));
    }
}