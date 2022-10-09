<?php


class ExampleTest extends \core\TestCase
{


    // 测试config
    public function testDatabaseDefault()
    {
        // 断言内容是 "mysql_one"
        $this->assertEquals('mysql_one',
            config('database.default')
        );
    }


    // 测试路由
    public function testGetRoute()
    {
        $this->get('/hello')
            ->assertStatusCode(200); // 断言状态码是200
    }


    // 测试路由
    public function testPostRoute()
    {
        $res = $this->get('/hello',['name'=>'zhangsan','age'=>18]);


        // 断言返回的内容是 "你在访问hello"
        $this->assertEquals('你在访问hello',
            $res->getContent());

    }


}