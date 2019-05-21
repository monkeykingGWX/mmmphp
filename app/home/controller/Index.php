<?php

namespace app\home\controller;

use mmmphp\lib\App;

class Index extends App
{
    public function index ()
    {
        $this->assign('name', 'gwx');
        $this->display();
    }
}