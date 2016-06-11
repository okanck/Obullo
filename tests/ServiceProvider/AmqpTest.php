<?php

namespace ServiceProvider\Test;

use AMQPConnection;
use RuntimeException;
use PHPUnit_Framework_TestCase;

class AmqpTest extends PHPUnit_Framework_TestCase
{
    protected $container;
    protected $AMQPClass;
    protected $AMQPConnection;

    /**
     * Setup variables
     *
     * @return void
     */
    public function setUp()
    {
        global $container;
        $this->container = $container;
        $this->container->addServiceProvider('Obullo\Container\ServiceProvider\Amqp');
        $this->AMQPConnection = $this->container->get('amqp')->shared(['connection' => 'default']);
        $this->AMQPClass = (get_class($this->AMQPConnection) == 'AMQPConnection') ? 'AMQPConnection' : 'PhpAmqpLib\Connection\AMQPConnection';

    }

    /**
     * Test extension
     * 
     * @return void
     */
    public function testExtensionIsLoaded()
    {
        $this->assertInstanceOf($this->AMQPClass, $this->AMQPConnection);
    }

    /**
     * Shared
     * 
     * @return void
     */
    public function testShared()
    {
        $AMQPConnectionShared = $this->container->get('amqp')->shared(['connection' => 'default']);

        $this->assertInstanceOf($this->AMQPClass, $this->AMQPConnection, "I expect that the value is instance of $this->AMQPClass.");
        $this->assertSame($this->AMQPConnection, $AMQPConnectionShared, "I expect that the two variables reference the same object.");
    }

    /**
     * Factory
     * 
     * @return void
     */
    public function testFactory()
    {
        $AMQPConnectionFactory = $this->container->get('amqp')->factory(
            [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => '123456',
                'vhost' => '/'
            ]
        );
        $this->assertNotSame($this->AMQPConnection, $AMQPConnectionFactory, "I expect that the shared and factory instances are not the same object.");

        $AMQPConnectionNewFactory = $this->container->get('amqp')->factory(
            [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => '123456',
                'vhost' => '/',
                'test' => null  // Change something
            ]
        );
        $this->assertNotSame($AMQPConnectionFactory, $AMQPConnectionNewFactory, "I expect that the old factory and new factory instances are not the same object.");
    }

}