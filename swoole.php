<?php

use core\request\PhpRequest;

error_reporting(E_ALL);
ini_set("display_errors","On");


require_once  __DIR__ . '/vendor/autoload.php'; // 引入自动加载
require_once __DIR__ . '/app.php';   // 框架的文件

$start = function()
{
    Swoole\Runtime::enableCoroutine($flags = SWOOLE_HOOK_ALL);
    // 如果你用了CURL PDO之类的客户端 请开启这个客户端协程化
    // 详细见文档: https://wiki.swoole.com/#/runtime

    // 绑定主机 端口
    $http = new Swoole\Http\Server('0.0.0.0', 9501);
    $http->set([
        // 进程pid文件
        'pid_file' => FRAME_BASE_PATH.'/storage/swoole.pid',
        'enable_coroutine' => true, // 开启异步协程化  默认开启
        'worker_num' => 4  // Worker进程数 跟协程处理有关系 https://wiki.swoole.com/#/server/setting?id=worker_num
    ]);


    // 绑定request
    app()->bind(\core\request\RequestInterface::class,function () {
        return \core\SwooleContext::get('request');
    },false);

    // 肯定要设置成false 让他每次都调用

    $http->on('request', function ($request, $response) {

        echo Swoole\Coroutine::getCid().PHP_EOL; // 打印当前协程id
        // 文档:https://wiki.swoole.com/#/coroutine/coroutine?id=getcid

        // 绑定request 现在这个是有问题  因为容器的单例的  如果request会一直变
        // 应该使用协程上下文来保存request  下一篇文章会讲

        $server = $request->server;
        /*
         app()->bind(\core\request\RequestInterface::class,function () use ($server){
             return PhpRequest::create(
                 $server['path_info'],
                 $server['request_method'],
                 $server
             );
         },false);
        */

        // 用协程上下文保存当前的request
        \core\SwooleContext::put('request',PhpRequest::create(
            $server['path_info'],
            $server['request_method'],
            $server
        ));


        $response->end(
            app('response')->setContent( // 响应
                app('router')->dispatch( // 路由
                    app(\core\request\RequestInterface::class) // 请求
                )
            )->getContent()
        );

        \core\SwooleContext::delete();
        // 请求结束删除当前协程的变量 否则会内存泄漏

    });

    echo 'start ok'.PHP_EOL;
    $http->start();
};

$stop = function ()
{

    if(! file_exists(FRAME_BASE_PATH.'/storage/swoole.pid'))
        return;
    $pid = file_get_contents(FRAME_BASE_PATH.'/storage/swoole.pid');
    Swoole\Process::kill($pid); // 见文档
};

$handle = $argv[1];

// 启动
if( $handle == 'start')
    $start();

// 停止
elseif( $handle == 'stop');
$stop();
