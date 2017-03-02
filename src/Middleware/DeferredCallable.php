<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:49
 */
namespace Middleware;

use Closure;

class DeferredCallable
{
    use CallableResolverAwareTrait;

    private $callable;

    private $container;

    /**
     * 初始化
     */
    public function __construct($callable,  $container = null)
    {

        $this->callable = $callable;
        $this->container = $container;
    }

    public function __invoke()
    {
        $callable = $this->resolveCallable($this->callable);
        if ($callable instanceof Closure) {
            $callable = $callable->bindTo($this->container);
        }

        $args = func_get_args();

        return call_user_func_array($callable, $args);
    }
}
