<?php

use PastryBag\Di\PastryBag;

$diConfig = [];
$registryFile = CONFIG . 'container_configs.php';
if (file_exists($registryFile)) {
    $diConfig += require_once $registryFile;
}
$diConfig[] = \PastryBag\Config\Common::class;
$di = PastryBag::create($diConfig);
PastryBag::setContainer($di);
