<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:42
 */
namespace Middleware;

use RuntimeException;


trait CallableResolverAwareTrait
{
    protected function resolveCallable($callable)
    {
        $resolver = $this->container->build('Middleware\callableResolver');

        return $resolver->resolve($callable);
    }
}
