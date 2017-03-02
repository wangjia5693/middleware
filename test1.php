<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 15:18
 */
class ResourceServerMiddleware
{

    /**
     */
    public function __invoke($request, $next)
    {

        $request = array_merge($request,array(11111,2222));
        // Pass the request and response on to the next responder in the chain
        return $next($request);
    }
}
