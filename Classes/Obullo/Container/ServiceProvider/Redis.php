<?php

namespace Obullo\Container\ServiceProvider;

class Redis extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'redis'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('redis', 'Obullo\Container\ServiceProvider\Connector\Redis')
            ->withArgument($container)
            ->withArgument(
                array(
                    'connections' => 
                    [
                        'default' => [
                            'host' => '127.0.0.1',
                            'port' => 6379,
                            'options' => [
                                'persistent' => false,
                                'auth' => '',
                                'timeout' => 30,
                                'attempt' => 100,
                                'serializer' => 'none',
                                'database' => null,
                                'prefix' => null,
                            ]
                        ],
                        'second' => [
                            'host' => '127.0.0.1',
                            'port' => 6379,
                            'options' => [
                                'persistent' => false,
                                'auth' => '',
                                'timeout' => 30,
                                'attempt' => 100,
                                'serializer' => 'php',
                                'database' => null,
                                'prefix' => null,
                            ]
                        ],
                    ]
                )
            );
    }
}