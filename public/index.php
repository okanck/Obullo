<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Define the root
 */
define('ROOT', dirname(__DIR__).'/');

/**
 * Step 1: Require the Obullo using Composer's autoloader
 *
 * If you are not using Composer, you need to load Obullo with your own
 * PSR-4 autoloader.
 */
require ROOT .'constants.php';
require ROOT .'vendor/autoload.php';

/**
 * Step 2: Instantiate a Container to load your service providers.
 */
$container = new League\Container\Container;

$container->share('request', Obullo\Http\ServerRequestFactory::fromGlobals());
$container->share('response', new Zend\Diactoros\Response);
$container->share('router', new Obullo\Router\Router($container, ['defaultHandler' => true]));
/**
 * Step 3: Instantiate a Obullo application
 * 
 * This example instantiates a Obullo application using
 * your default middlewares.
 */
$app = new Obullo\App($container);

/**
 * Step 4: Add your middlewares
 */
// $app->add('Translation');
// $app->add('ParsedBody');

/**
 * Step 5: Add your service providers
 */
$app->addServiceProvider('Obullo\Container\ServiceProvider\Config');
$app->addServiceProvider('Obullo\Container\ServiceProvider\Cookie');
$app->addServiceProvider('Obullo\Container\ServiceProvider\View');
$app->addServiceProvider('Obullo\Container\ServiceProvider\Logger');

// $app->addProvider('Obullo\Container\ServiceProvider\Amqp');
// $app->addProvider('Obullo\Container\ServiceProvider\Database');
// $app->addProvider('Obullo\Container\ServiceProvider\Redis');
// $app->addProvider('Obullo\Container\ServiceProvider\Memcached');
// $app->addProvider('Obullo\Container\ServiceProvider\Mongo');

/**
 * Step 6: Define your server using Zend Diactoros
 */
$server = Zend\Diactoros\Server::createServerfromRequest(
    $app,
    $container->get('request'),
    $container->get('response')
);

/**
 * Step 7: Emit output
 *
 * This method should be called last. This executes the Obullo application
 * and returns the HTTP response to the HTTP client.
 */
$server->listen();