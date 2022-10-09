<?php

namespace core\database\connection;


// 链接的基础类
class Connection
{
    protected $pdo;
    protected $tablePrefix;
    protected $config;
    public function __construct($pdo, $config)
    {

        $this->pdo = $pdo;
        $this->tablePrefix = $config['prefix'];
        $this->config = $config;
    }


}