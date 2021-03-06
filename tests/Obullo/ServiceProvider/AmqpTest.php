<?php

use League\Container\Container;

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
        $this->container = new Container;
        $this->container->addServiceProvider('AppBundle\ServiceProvider\Amqp');
        $this->AMQPConnection = $this->container->get('amqp:default');
        
        $this->AMQPClass = (get_class($this->AMQPConnection) == 'AMQPConnection') ? 'AMQPConnection' : 'PhpAmqpLib\Connection\AMQPConnection';
    }

    /**
     * Shared
     *
     * @return void
     */
    public function testShared()
    {
        $AMQPConnectionShared = $this->container->get('amqp:default');

        $this->assertInstanceOf($this->AMQPClass, $this->AMQPConnection, "I expect that the value is instance of $this->AMQPClass.");
        $this->assertSame($this->AMQPConnection, $AMQPConnectionShared, "I expect that the two variables reference the same object.");
    }
}
