# PastryBag

[![Packagist](https://img.shields.io/packagist/l/eryw/pastry-bag.svg?style=flat-square)](LICENSE)
[![Travis branch](https://img.shields.io/travis/eryw/pastry-bag/master.svg?style=flat-square)](https://travis-ci.org/eryw/pastry-bag)
[![Packagist](https://img.shields.io/packagist/v/eryw/pastry-bag.svg?style=flat-square&label=stable)](https://packagist.org/packages/eryw/pastry-bag)

Dependencies injection plugin for CakePHP 3. This plugin provides a constructor injection and a controller action injection.

# Installation
#### Composer
```bash
composer require eryw/pastry-bag=@stable
```

#### Configuration
Add the following line to your `config/bootstrap.php`:
```php
Plugin::load('PastryBag', ['bootstrap' => true]);
```
##### For CakePHP 3.2 with dispatch filter #####
Replace your controller factory filter in `config/bootstrap.php`:
```php
DispatcherFactory::add('ControllerFactory');
```
With the following:
```php
DispatcherFactory::add('PastryBag\Routing\Filter\ControllerFactoryFilter');
```
##### For CakePHP 3.3 with middleware #####
Please override method `getDispatcher()` on your `Application` class
```php
class Application extends BaseApplication
{
    // ... //
    
    protected function getDispatcher()
    {
        return new ActionDispatcher(new ControllerFactory(), null, DispatcherFactory::filters());
    }
}
```

To make the dependency injection works, all controller must extends `PastryBag\Controller\Controller`.

So, change your `AppController` parent class from `Cake\Controller\Controller` into `PastryBag\Controller\Controller`:
```php
class AppController extends \PastryBag\Controller\Controller
{
    // ... //
}
```

# Usage
#### Constructor Injection
```php
class UsersController extends AppController
{
    protected $payment;

    public function __construct(PaymentService $payment)
    {
        parent::__construct();
        $this->payment = $payment;
    }
    
    public function payBill()
    {
        // `$this->payment` will auto injected with instance of PaymentService
        $this->payment->anyMethodOfPaymentService();
    }
}
```

#### The controller method injection
```php
class RemoteGaleryController extends AppController
{
    public function index($id, MyHttpClient $client)
    {
        // `$client` will auto injected with instance of MyHttpClient
        $client->request('GET', 'http://remotesite.com');
    }
}
```
##### Note:
Only the type hinted parameters will auto injected.

# Config
This plugin uses [Aura.Di](https://github.com/auraphp/Aura.Di) as container. Configuration should be put inside a class that implements `Aura\Di\ContainerConfigInterface` and the list of configuration (class name or instance) must be put at `config/container_configs.php`

Example content of `config/container_configs.php`:
```php
use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Cake\ORM\TableRegistry;

// OPTIONAL. You can move this class to other file if you want
class DiConfig extends ContainerConfig
{
    public function define(Container $di)
    {
        $di->set(\App\Model\Table\UsersTable::class, $di->lazy(function () {
            return TableRegistry::get('Users');
        }));
        $di->types[\App\Model\Table\UsersTable::class] = $di->lazyGet(\App\Model\Table\UsersTable::class);
    }
}

// REQUIRED. This file must return list of configs as array
return [
    'My\Awesome\App\ClassImplementsAuraDiContainerConfigInterface',
    new DiConfig,
];
```
For advance container configuration and usage, please check [Aura.Di](https://github.com/auraphp/Aura.Di) official documentation

If you want to access the instance of container directly, you can use static method `PastryBag::getContainer()`:
```php
// `$di` is instance of Aura\Di\Container
$di = PastryBag::getContainer();
```

This plugin is inspired by [PipingBag](https://github.com/lorenzo/piping-bag) plugin but does not require annotations.