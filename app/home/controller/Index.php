<?php

namespace app\home\controller;

use mmmphp\lib\Controller;
use mmmphp\lib\Verify;
use mmmphp\lib\Image;

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

    public function image ()
    {
        $image = new Image(__DIR__.'/../timg.jpg');
        // 图片裁剪测试 从坐标（0,0）裁出 宽为100高为100的区域，保存为50*50的大小
        //$image->crop(100, 100, 0, 0, 50, 50)->output();


        // 图像另存为测试
        //$image->crop(100, 100, 50, 50, 50, 50 )->save(__DIR__ . '/../crop.jpg');


        /* 缩放测试 */
        // 等比例缩放测试
        //$image->thumb(20000,150)->output();

        // 居中裁剪测试(不会变形，但裁剪的不完整)
        //$image->thumb(200,100, Image::THUMB_CENTER)->output();

        // 左上角裁剪测试(不会变形，但裁剪的不完整)
        //$image->thumb(200,100, Image::THUMB_NORTHWEST)->output();

        // 右下角裁剪测试(不会变形，但裁剪的不完整)
        //$image->thumb(200,100, Image::THUMB_SOUTHEAST)->output();

        // 填充裁剪(不会变形)
        //$image->thumb(600, 300, Image::THUMB_FILLED)->output();

        // 固定裁剪(图片会变形)
        //$image->thumb(600, 300, Image::THUMB_FIXED)->output();


        /* 水印测试 */
        //$image->water(__DIR__ . '/../logo.png')->output();


        /* 文字水印 */
        $image->text('MMMPHP ', MMMPHP_PATH . '/lib/verify/ttf/1.ttf',30)->output();
    }
}