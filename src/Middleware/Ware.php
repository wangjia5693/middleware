<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:32
 */
namespace Middleware;

use Exception;
use Throwable;
use Closure;
use InvalidArgumentException;
use Middleware\Container as Container;

class Ware
{
    use MiddlewareAwareTrait;

    private $container;

    public function __construct($container = [])
    {
        if (is_array($container)) {
            $container = new Container($container);
        }
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Add middleware
     */
    public function add($callable)
    {
        //将传过来的中间件实例重新包装
        return $this->addMiddleware(new DeferredCallable($callable, $this->container));
    }
    public function __invoke( $request)
    {
        var_dump($request);
    }
}