<?php

namespace app\controller;

use mmmphp\App;

class Index extends App
{
    public function index ()
    {
        $this->assign('name', 'gwx');
        $this->display();
    }
}