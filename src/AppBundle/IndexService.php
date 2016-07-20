<?php

namespace AppBundle;

use Obullo\Mvc\Bundle\LiteralMatch;
use Obullo\Mvc\Bundle\BundleInterface;

class IndexService implements BundleInterface
{
    protected $app;
    protected $match;

    public function __construct($pattern)
    {
        $this->match = new LiteralMatch($pattern);
    }

    public function setApplication($app)
    {
        $this->app = $app;
    }

    public function addServiceProviders()
    {
        $container = $this->app->getContainer();

        $container->addServiceProvider('AppBundle\ServiceProvider\Config');
        $container->addServiceProvider('AppBundle\ServiceProvider\Cookie');
        $container->addServiceProvider('AppBundle\ServiceProvider\Mvc');
        $container->addServiceProvider('AppBundle\ServiceProvider\View');
        $container->addServiceProvider('AppBundle\ServiceProvider\Logger');
        $container->addServiceProvider('AppBundle\ServiceProvider\Database');
        $container->addServiceProvider('AppBundle\ServiceProvider\Cache');
        // $container->addServiceProvider('AppBundle\ServiceProvider\Amqp');
        $container->addServiceProvider('AppBundle\ServiceProvider\Redis');
        // $container->addServiceProvider('AppBundle\ServiceProvider\Memcached');
        // $container->addServiceProvider('AppBundle\ServiceProvider\Mongo');
    }

    public function addMiddlewares()
    {
        // $this->app->add('ParsedBody');
    }

    public function getName()
    {
        $reflection = new \ReflectionClass($this);
        return $reflection->getNamespaceName();
    }

    public function getMatch()
    {
        return $this->match;
    }

}