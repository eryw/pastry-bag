# PastryBag

Dependencies injection plugin for CakePHP 3. This plugin provides the constructor injection and the controller method injection.

# Installation
#### Composer
```bash
composer require eryw/pastry-bag=dev-master
```

#### Configuration
Add the following line to your `config/bootstrap.php`:
```php
Plugin::load('PastryBag', ['bootstrap' => true]);
```
Then replace your controller factory filter in `config/bootstrap.php`:
```php
DispatcherFactory::add('ControllerFactory');
```
With the following:
```php
DispatcherFactory::add('PastryBag\Routing\Filter\ControllerFactoryFilter');
```
To make the dependency injection works, all controller must extends `PastryBag\Controller\Controller`.

So, change your `AppController` parent class from `Cake\Controller\Controller` into `PastryBag\Controller\Controller`:
```php
class AppController extends \PastryBag\Controller\Controller
{

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
This plugin uses [Aura.Di](https://github.com/auraphp/Aura.Di) as dependency injection container. Configuration should be put inside of class that implements `Aura\Di\ContainerConfigInterface` and list of configuration must be put at `config/config_registry.php`

Example content of `config/config_registry.php`:
```php
use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Cake\ORM\TableRegistry;

// OPTIONAL. You can move this class to other file if you want
class DiConfig extends ContainerConfig
{
    public function define(Container $di)
    {
        $di->set('UsersTable', $di->lazy(function () {
            return TableRegistry::get('Users');
        }));
        $di->types[\App\Model\Table\UsersTable::class] = $di->lazyGet('UsersTable');
    }
}

// REQUIRED. This file must return list of configs as array
return [
    'My\Awesome\App\ClassImplementsAuraDiContainerConfigInterface',
    new DiConfig,
];
```
For advance container configuration and usage, please check [Aura.Di](https://github.com/auraphp/Aura.Di) official documentation

If you want to access the instance of container directly, you can use static method `PastryBag::container()`:
```php
// `$di` is instance of Aura\Di\Container
$di = PastryBag::container();
```

This plugin is inspired by [PipingBag](https://github.com/lorenzo/piping-bag) plugin but without needing annotation.