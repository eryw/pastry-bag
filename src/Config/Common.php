<?php
namespace PastryBag\Config;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

class Common extends ContainerConfig
{
    public function define(Container $di)
    {
        $di->set(Container::class, $di);
        $di->types[Container::class] = $di->lazyGet(Container::class);
    }
}