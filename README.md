# Dependency Injection Container Plugin for CakePHP 3

This plugin adds the ability to configure object instances and their dependencies before they are used,
and to store them into a container class to easy access.

It uses the clean and flexible [Ray.Di Library](https://github.com/koriym/Ray.Di) which is a PHP dependency
injection framework in the style of "Google Guice".

Ray.Di also allows you to program using `AOP`, that is, decorating the configured instances so some logic
can be run before or after any of their methods.

## Installation

You can install this plugin into your CakePHP application using
[composer](http://getcomposer.org).

```bash
composer require lorenzo/piping-bag=dev-master
```

## Configuration

You will need to add the following line to your application's bootstrap.php file:

```php
Plugin::load('PipingBag', ['bootstrap' => true]);
```

Additionally, you can configure the modules to be used and caching options in your `config/app.php` file.

```php
'PipingBag' => [
	'modules' => ['MyWebModule', 'AnotherModule', 'APlugin.SuperModule'],
	'cacheConfig' => 'default'
]
```

Modules can also be returned as instances in the configuration array:

```php
'PipingBag' => [
	'modules' => [new App\Di\Module\MyWebModule()],
	'cacheConfig' => 'default'
]
```

Finally, if you wish to tune your modules before they are registered, you can use a callable function:

```php
'PipingBag' => [
	'modules' => function () {
		return [new MyWebModule()];
	},
	'cacheConfig' => 'default'
]
```

## What is a Module anyway?

Modules are classes that describe how instances and their dependencies should be constructed, they provide a
natural way of grouping configurations. An example module looks like this:

```php
// in app/src/Di/Module/MyModule.php

namespace App\Di\Module;

use Ray\Di\AbstractModule;
class MyModule extends AbstractModule
{
    public function configure()
    {
        $this->bind('MovieApp\FinderInterface')->to('MovieApp\Finder');
		$this->bind('MovieApp\HttpClientInterface')->to('Guzzle\HttpClient');
		$this->install(new OtherModule()); // Modules can install other modules
    }
}
```

Modules are, by convention, placed in your `src/Di/Module` folder. Read more about creating modules and
how to bind instances to names in the [Official Ray.Di Docs](https://github.com/koriym/Ray.Di/tree/develop#getting-stated).

## Usage

After creating and passing the modules in the configuration, you can get instance of any class and have their dependencies
resolved following the rules created in the modules:

```php
use PipingBag\Di\PipingBag;

$httpClient = PipingBag::get('MovieApp\HttpClientInterface');
```

### Injecting Dependencies in Controllers

Ray.Di is able to inject instances to your controllers based on annotations:

```php
// in src/Controller/ArticlesController.php

use App\Controller\AppController;
use MovieApp\HttpClientInterface;
use Ray\Di\Di\Inject; // This is important

class ArticlesController extends AppController
{

    /**
     * @Inject
     */
    public function setHttpClient(HttpClientInterface $connection)
    {
        $this->httpClient = $connection;
    }
}
```

As soon as the controller is created, all methods having the `@Inject` annotation will get
instances of the hinted class passed. This works for constructors as well.

### Injecting Dependencies in Shells

Shells are also able to receive dependencies via the `@Inject` annotation:

```php

use Cake\Console\Shell;
use Ray\Di\Di\Inject; // This is important

class MyShell extends Shell
{
    /**
     * @Inject
     */
    public function setHttpClient(HttpClientInterface $connection)
    {
        $this->httpClient = $connection;
    }

}
```
