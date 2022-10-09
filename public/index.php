<?php


require __DIR__.'/../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once  __DIR__.'/../app.php';

// 绑定request
App::getContainer()->bind(\core\request\RequestInterface::class,function (){
    return \core\request\PhpRequest::create(
        $_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD'],$_SERVER
    );
});


App::getContainer()->get('response')->setContent(
    App::getContainer()->get('router')->dispatch(
        App::getContainer()->get(\core\request\RequestInterface::class)
    )
)->send();


