<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 15:22
 * demo案例，可以不断的扩展中间件；
 *
 * 中间件类需要实现一个__invoke方法；使用splstack存储中间件，所以执行循序是越后添加的越先执行；
 *
 */
spl_autoload_register(function($classname){
    require_once __DIR__."/src/".$classname.".php";
});
include_once __DIR__."/test1.php";
include_once __DIR__."/test2.php";

$app = new \Middleware\Ware();
$app->add(
    new ResourceServerMiddleware()
);
$app->add(
    new TestServerMiddleware()
);
$app->callMiddlewareStack(array('dsd'));
