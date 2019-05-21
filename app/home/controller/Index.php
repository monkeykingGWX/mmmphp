<?php

namespace app\home\controller;

use mmmphp\lib\App;

class Index extends App
{
    public function index ()
    {
        echo 'hello world';
        $this->display();
    }
}