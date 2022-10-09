<?php

namespace core;

use core\database\connection\MysqlConnection;

class Database
{
    protected $connections = []; // all connection

    // 获取默认链接
    protected function getDefaultConnection()
    {
        return \App::getContainer()->get('config')->get('database.default');
    }


    // 设置默认链接
    public function setDefaultConnection($name)
    {
        \App::getContainer()->get('config')->set('database.default', $name);
    }

    // 根据配置信息的name来创建链接
    public function connection($name = null)
    {

        if (isset($this->connections[$name])) // 如果存在就直接返回
            return $this->connections[$name];

        if ($name == null) // 选择默认链接
            $name = $this->getDefaultConnection();

        $config = \App::getContainer()->get('config')->get('database.connections.' . $name); // 获取链接的配置

        $connectionClass = null; // 链接处理的类
        switch ($config['driver']) {
            case 'mysql':
                $connectionClass = MysqlConnection::class;
                break;
             // 如果有其他类型的数据库 那就继续完善
        }
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        try {
            $pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        return $this->connections[$name] = new $connectionClass($pdo, $config);
    }

    // 代理模式
    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }

}