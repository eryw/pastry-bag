<?php

use PastryBag\Di\PastryBag;

$diConfig = [];
$registryFile = CONFIG . 'config_registry.php';
if (file_exists($registryFile)) {
    $diConfig += require_once $registryFile;
}

$di = PastryBag::create($diConfig);

PastryBag::container($di);
