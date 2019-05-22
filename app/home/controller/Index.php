<?php

namespace app\home\controller;

use mmmphp\lib\Controller;

class Index extends Controller
{
    public function index ()
    {
        if (isPost()) {
            $upload = new \mmmphp\lib\Upload();
            $upload->uploadSomeFile();
        } else {
            $this->assign('name', 'gwx');
            $this->display();
        }
    }
}