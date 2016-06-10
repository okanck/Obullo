<?php

namespace Obullo\Router;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use SplQueue;
use Obullo\Router\Group;
use InvalidArgumentException;
use Http\Middleware\NotAllowed;
use Obullo\Utils\Route as RouteHelper;
use Obullo\Router\Filter\FilterTrait;
use Interop\Container\ContainerInterface as Container;

/**
 * Router
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Router implements RouterInterface
{
    use AddTrait;
    use FilterTrait;

    protected $path;
    protected $group;
    protected $class;
    protected $queue;
    protected $folder;
    protected $method;
    protected $handler;
    protected $request;
    protected $ancestor;
    protected $response;
    protected $container;
    protected $count = 0;
    protected $routes = array();
    protected $dispatched = false;
    protected $resolveCurrentPath = false;

    /**
     * Constructor
     * 
     * @param Container $container container
     * @param array     $options   options
     *
     * @return void
     */
    public function __construct(Container $container, array $options)
    {
        $this->path      = $container->get('request')->getUri()->getPath();
        $this->request   = $container->get('request');
        $this->response  = $container->get('response');
        $this->container = $container;

        $this->resolveCurrentPath = $options['resolveCurrentPath'];
    }

    /**
     * Rewrite all http requests
     * 
     * @param string $method  method
     * @param string $pattern regex pattern
     * @param string $rewrite replacement path
     * 
     * @return void
     */
    public function rewrite($method, $pattern, $rewrite)
    {
        if (in_array($this->request->getMethod(), (array)$method)) {
            $pattern    = "/".ltrim($pattern, "/");
            $path       = preg_replace('#^'.$pattern.'$#', $rewrite, $this->path);
            $this->path = '/'.ltrim($path, '/');
        }
    }

    /**
     * Create a route
     * 
     * @param string $method  method
     * @param string $pattern regex pattern
     * @param mixed  $handler mixed
     * 
     * @return void
     */
    public function map($method, $pattern, $handler = null)
    {
        ++$this->count;
        $this->routes[$this->count] = [
            'method' => (array)$method,
            'pattern' => "/".ltrim($pattern, "/"),
            'handler' => $handler,
            'middlewares' => array()
        ];
        return $this;
    }

    /**
     * Create group
     * 
     * @param string   $pattern  pattern
     * @param callable $callable callable
     * 
     * @return object
     */
    public function group($pattern, $callable)
    {   
        if (! is_callable($callable)) {
            throw new InvalidArgumentException("Group method second parameter must be callable.");
        }
        $this->group = ($this->group == null) ? new Group($this->request) : $this->group;
        $this->group->enqueue($pattern, $callable);
        return $this->group;
    }

    /**
     * Route process
     * 
     * @return void
     */
    protected function dispatch()
    {
        foreach ($this->routes as $r) {

            if (! in_array($this->request->getMethod(), (array)$r['method'])) {
                $this->queue->enqueue(['callable' => new NotAllowed, 'params' => (array)$r['method']]);
                continue;
            }
            $handler = $r['handler'];
            $pattern = $r['pattern'];
            
            if (trim($pattern, "/") == trim($this->path, "/") || preg_match('#^'.$pattern.'$#', $this->path, $params)) {

                $this->queue($r['middlewares']);

                if (is_string($handler)) {
                    if (strpos($handler, '$') !== false && strpos($pattern, '(') !== false) {
                        $handler = preg_replace('#^'.$pattern.'$#', $handler, $this->path);
                    }
                    $this->handler = $handler;
                }
                if (is_callable($handler)) {
                    array_shift($params);
                    $this->handler = $handler($this->request, $this->response, array_values($params));
                }
            }
        }
        $this->setDefaultHandler();
        $this->dispatched = true;
    }

    /**
     * Set default path as handler ( Resolves current path if has no route match )
     *
     * @return void
     */
    protected function setDefaultHandler()
    {
        if ($this->handler == null && $this->resolveCurrentPath) {
            $this->handler = $this->path;
        }
    }

    /**
     * Group process
     * 
     * @return void
     */
    public function popGroup()
    {
        if ($this->group == null) {
            return;
        }
        $exp   = explode("/", trim($this->path, "/"));
        $group = $this->group->dequeue();

        if (in_array(trim($group['pattern'], "/"), $exp, true)) {
            $group['callable']($this->request, $this->response);
            $this->queue($group['middlewares']);
        }
        if (! $this->group->isEmpty()) {
            $this->popGroup();
        }
    }

    /**
     * Get executed handler result
     * 
     * @return object|string
     */
    public function getHandler()
    {
        if (! $this->dispatched) {  // Run one time, this function runs twice
            $this->popGroup();      // in App.php invoke() method.
            $this->dispatch();
        }
        return $this->handler;
    }

    /**
     * Set queue for middlewares
     * 
     * @param SplQueue $queue queue
     *
     * @return void
     */
    public function setQueue(SplQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Queue middlewares
     * 
     * @param array $middlewares middlewares
     * 
     * @return void
     */
    protected function queue($middlewares)
    {
        if (empty($middlewares)) {
            return;
        }
        foreach ((array)$middlewares as $value) {
            $middleware = '\Http\Middleware\\'.$value['name'];
            if (! class_exists($middleware, false)) {
                $this->queue->enqueue(['callable' => new $middleware, 'params' => $value['params']]);
            }
        }
    }

    /**
     * Add middleware
     * 
     * @param string $name middleware name
     * @param array  $args arguments
     *
     * @return void
     */
    protected function middleware($name, array $args)
    {
        $this->routes[$this->count]['middlewares'][] = array('name' => $name, 'params' => $args);
    }

    /**
     * Set the class name
     * 
     * @param string $class classname segment 1
     *
     * @return object Router
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Set current method
     * 
     * @param string $method name
     *
     * @return object Router
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set the folder name : It must be lowercase otherwise folder does not work
     *
     * @param string $folder folder
     * 
     * @return object Router
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * Sets top folder http://example.com/api/user/delete/4
     * 
     * @param string $folder sets top folder
     *
     * @return void
     */
    public function setAncestor($folder)
    {
        $this->ancestor = $folder;
    }

    /**
     * Get primary folder
     *
     * @param string $separator get folder seperator
     * 
     * @return void
     */
    public function getAncestor($separator = '')
    {
        return (empty($this->ancestor)) ? '' : htmlspecialchars($this->ancestor).$separator;
    }

    /**
     * Get folder
     *
     * @param string $separator get folder seperator
     * 
     * @return string
     */
    public function getFolder($separator = '')
    {
        return (empty($this->folder)) ? '' : htmlspecialchars($this->folder).$separator;
    }

    /**
     * Returns to current routed class name
     *
     * @return string
     */
    public function getClass()
    {
        return htmlspecialchars(RouteHelper::ucwords($this->class));
    }

    /**
     * Returns to current method
     * 
     * @return string
     */
    public function getMethod()
    {
        return htmlspecialchars($this->method);
    }

    /**
     * Returns php namespace of the current route
     * 
     * @return string
     */
    public function getNamespace()
    {
        $folder = $this->getFolder();
        if (strpos($folder, "/") > 0) {  // Converts "Tests\Authentication/storage" to Tests\Authentication\Storage
            $exp = explode("/", $folder);
            $folder = trim(implode("\\", $exp), "\\");
        }
        $namespace = RouteHelper::ucwords($this->getAncestor()).'\\'.RouteHelper::ucwords($folder);
        $namespace = trim($namespace, '\\');
        return (empty($namespace)) ? '' : $namespace.'\\';
    }

    /**
     * Get master request router
     * 
     * @return object
     */
    public function getMaster()
    {
        if ($this->container->has('router.master')) {
            return $this->container->get('router.master');   
        }
        return $this->container->get('router');
    }

    /**
     * Clean all data for Layers
     *
     * @return void
     */
    public function clear()
    {
        $this->class = '';
        $this->folder = '';
        $this->ancestor = '';
    }

}