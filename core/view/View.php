<?php

namespace core\view;

class View
{

    protected $engin;

    //传入实现者
    public function __construct(ViewInterface $engin)
    {
        $this->engin = $engin;
        $this->engin->init();
    }

    // 利用代理模式 来调用
    public function __call($method, $args)
    {
        return  $this->engin->$method(...$args);
    }
}