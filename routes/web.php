<?php
/**
 * @var $router \Core\RouteCollection
 */

$router->get('/hello',function (){
   return '你在访问hello';
});//->middleware(\App\middleware\WebMiddleWare::class);

$router->get('/config',function (){
    echo  App::getContainer()->get('config')->get('database.connections.mysql_one.driver').'<hr/>';
    App::getContainer()->get('config')->set('database.connections.mysql_one.driver','mysql set');
    echo  App::getContainer()->get('config')->get('database.connections.mysql_one.driver');
});


$router->get('/db',function (){
    $id = 1;
    var_dump(
        App::getContainer()->get('db')->select('select * from users where id = ?',[$id])
    );

});
$router->get('/query',function (){
    $id = 1;
    var_dump(
        App::getContainer()->get('db')->table('users')->where('id',2)->get()
        //select('select * from users where id = ?',[$id])
    );

});

$router->get('/model',function (){
    $users = \App\User::Where('id',1)->orWhere('id',2)->get();
    foreach ($users as $user)
        echo $user->sayPhp()."<br/>";
});


$router->get('/controller','UserController@index');


// 把他放在app/helpers.php  会更好 遵守规范
// 为什么我不放? 只要读者懂就行 我放要多写一个介绍了
function view($path,$params = [])
{
    return App::getContainer()->get(\core\view\ViewInterface::class)->render($path,$params);
}


// blade的模板引擎 默认使用
$router->get('view/blade', function (){
    $str = '这是blade模板引擎';

    // 跟laravel一样的用法
    return view('blade.index',compact('str'));
});


$router->get('view/thinkphp', function (){
    $str = '这是thinkphp模板引擎';

    // 跟laravel一样的用法
    return view('thinkphp.index',compact('str'));
});



$router->get('log/stack', function (){

    // 把{language}替换成php
    App::getContainer()->get('log')->debug('{language} is the best language in the world',['language' => 'php']);
    App::getContainer()->get('log')->info("hello world");
});

$router->get('exception',function (){
    // 服务器不想鸟你并抛出了异常
    throw new \App\exceptions\ErrorMessageException('The server did not want to bird you and threw an exception');
});

$router->get('error',function (){
    helloworld; // 故意弄错 让它记录到日志
});
