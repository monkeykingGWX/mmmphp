<?php

namespace app\home\controller;

use mmmphp\lib\Controller;
use app\home\model\Tdb;

class Index extends Controller
{
    public function index ()
    {
        $this->assign('name', 'gwx');
        $this->display();
    }
}