<?php

namespace app\home\controller;

use mmmphp\lib\Controller;
use mmmphp\lib\Verify;

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

    public function check ()
    {
        $code = $_GET['code'];
        $verify = new Verify();
        var_dump($verify->check($code));
    }

    public function verify ()
    {
        $verify = new Verify();
        $verify->entry();
    }
}