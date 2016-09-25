<?php

use Cake\Core\Plugin;

require_once 'vendor/autoload.php';

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', dirname(__DIR__) . DS);
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('CORE_TESTS', CORE_PATH . 'tests' . DS);
define('TEST_APP', CORE_TESTS . 'test_app' . DS);
define('CONFIG', TEST_APP . 'config' . DS);

require_once CORE_PATH . 'config/bootstrap.php';

Plugin::load('PastryBag', ['path' => ROOT, 'bootstrap' => true]);