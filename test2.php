<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 15:18
 */
class TestServerMiddleware
{
    /**
     */
    public function __invoke($request, callable $next)
    {
        $request = array_merge($request,array(333,444));
        // Pass the request and response on to the next responder in the chain
        return $next($request);
    }
}
