<?php
/**
 * 图像处理类
 */
namespace mmmphp\lib;

class Image
{
    /* 缩略图相关常量定义 */
    const THUMB_SCALING   = 1; //常量，标识缩略图等比例缩放类型
    const THUMB_FILLED    = 2; //常量，标识缩略图缩放后填充类型
    const THUMB_CENTER    = 3; //常量，标识缩略图居中裁剪类型
    const THUMB_NORTHWEST = 4; //常量，标识缩略图左上角裁剪类型
    const THUMB_SOUTHEAST = 5; //常量，标识缩略图右下角裁剪类型
    const THUMB_FIXED     = 6; //常量，标识缩略图固定尺寸缩放类型

    /* 水印相关常量定义 */
    const WATER_NORTHWEST = 1; //常量，标识左上角水印
    const WATER_NORTH     = 2; //常量，标识上居中水印
    const WATER_NORTHEAST = 3; //常量，标识右上角水印
    const WATER_WEST      = 4; //常量，标识左居中水印
    const WATER_CENTER    = 5; //常量，标识居中水印
    const WATER_EAST      = 6; //常量，标识右居中水印
    const WATER_SOUTHWEST = 7; //常量，标识左下角水印
    const WATER_SOUTH     = 8; //常量，标识下居中水印
    const WATER_SOUTHEAST = 9; //常量，标识右下角水印

    // 图片资源句柄
    private $im = null;

    // 图片信息
    private $info = [];


    /**
     * @param string $img 图像路径
     * @throws \Exception
     */
    public function __construct(string $img)
    {
        if (!is_file($img)) {
            throw new \Exception('Image file is not exists');
        }

        $info = getimagesize($img);

        // 检测图像合法性
        if (empty($info)) {
            throw new \Exception('Illegal image file');
        }

        // 设置图像信息
        $this->info = [
            'width' => $info[0],
            'height' => $info[1],
            'type' => image_type_to_extension($info[2], false),
            'mime' => $info['mime']
        ];

        // 打开图像
        $open = 'imagecreatefrom' . $this->info['type'];
        $this->im = $open($img);

        if (empty($this->im)) {
            throw new \Exception('Failed to create image resources!');
        }
    }

    /**
     * @param int $w 裁剪宽度
     * @param int $h 裁剪高度
     * @param int $x 裁剪点x坐标
     * @param int $y 裁剪点y坐标
     * @param null $width 保留图宽度
     * @param null $height 保留图高度
     * @return $this
     */
    public function crop($w, $h, $x = 0, $y = 0, $width = null, $height = null)
    {
        // 设置保留尺寸
        $width  || $width = $w;
        $height || $height = $h;

        // 保留图像资源
        $dstIm = imagecreatetruecolor($width, $height);

        // 裁剪
        imagecopyresampled($dstIm, $this->im, 0, 0, $x, $y, $width, $height, $w, $h);

        // 清理原图资源
        imagedestroy($this->im);

        // 设置新图信息
        $this->im              = $dstIm;
        $this->info['width']  = $width;
        $this->info['height'] = $height;

        return $this;
    }

    /**
     * 输出图像
     * @return void
     */
    public function output ()
    {
        header("Content-Type:image/{$this->info['type']}");

        $output = 'image'.$this->info['type'];
        $output($this->im);
        imagedestroy($this->im);
    }

    /**
     * 保存图像
     * @param string $filename 图像路径
     * @param int $quality 清晰度
     * @return $this
     */
    public function save ($filename, $quality = 80)
    {
        // 图像另存为
        if ($this->info['type'] == 'jpg' || $this->info['type'] == 'jpeg') {
            imagejpeg($this->im, $filename, $quality);
        }

        $func = 'image' . $this->info['type'];
        $func($this->im, $filename);

        // 销毁图像资源
        imagedestroy($this->im);

        return $this;
    }

    /**
     * @param int $width 缩略图宽
     * @param int $height 缩略图高度
     * @param int $type 裁剪类型，默认为等比例缩放
     * @return $this|Image
     * @throws \Exception
     */
    public function thumb ($width, $height, $type = self::THUMB_SCALING)
    {
        // 原图宽度和高度
        $w = $this->info['width'];
        $h = $this->info['height'];

        switch ($type) {
            /* 等比例缩放 */
            case self::THUMB_SCALING:
                //原图尺寸小于缩略图尺寸则不进行缩略
                if ($w < $width && $h < $height) {
                    return $this;
                }
                //计算缩放比例
                $scale = min($width / $w, $height / $h);
                //设置缩略图的坐标及宽度和高度
                $x      = $y      = 0;
                $width  = $w * $scale;
                $height = $h * $scale;
                break;

            /* 居中裁剪 */
            case self::THUMB_CENTER:
                //计算缩放比例
                $scale = max($width / $w, $height / $h);
                //设置缩略图的坐标及宽度和高度
                $w = $width / $scale;
                $h = $height / $scale;
                $x = ($this->info['width'] - $w) / 2;
                $y = ($this->info['height'] - $h) / 2;
                break;

            /* 左上角裁剪 */
            case self::THUMB_NORTHWEST:
                //计算缩放比例
                $scale = max($width / $w, $height / $h);
                //设置缩略图的坐标及宽度和高度
                $x = $y = 0;
                $w = $width / $scale;
                $h = $height / $scale;
                break;

            /* 右下角裁剪 */
            case self::THUMB_SOUTHEAST:
                //计算缩放比例
                $scale = max($width / $w, $height / $h);
                //设置缩略图的坐标及宽度和高度
                $w = $width / $scale;
                $h = $height / $scale;
                $x = $this->info['width'] - $w;
                $y = $this->info['height'] - $h;
                break;

            case self::THUMB_FILLED:
                //计算缩放比例
                if ($w < $width && $h < $height) {
                    $scale = 1;
                } else {
                    $scale = min($width / $w, $height / $h);
                }
                //设置缩略图的坐标及宽度和高度
                $neww = $w * $scale;
                $newh = $h * $scale;
                $x    = $this->info['width'] - $w;
                $y    = $this->info['height'] - $h;
                $posx = ($width - $w * $scale) / 2;
                $posy = ($height - $h * $scale) / 2;

                //创建新图像
                $img = imagecreatetruecolor($width, $height);
                // 调整默认颜色
                $color = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $color);
                //裁剪
                imagecopyresampled($img, $this->im, $posx, $posy, $x, $y, $neww, $newh, $w, $h);
                imagedestroy($this->im); //销毁原图
                $this->im = $img;

                $this->info['width']  = (int) $width;
                $this->info['height'] = (int) $height;
                return $this;

            /* 固定 */
            case self::THUMB_FIXED:
                $x = $y = 0;
                break;
            default:
                throw new \Exception('不支持的缩略图裁剪类型');
        }

        // 裁剪图像
        return $this->crop($w, $h, $x, $y, $width, $height);
    }

    public function water ($water, $locate = self::WATER_SOUTHEAST)
    {
        if (!is_file($water)) {
            throw new \Exception('水印图片不存在');
        }
        // 水印图片信息
        $waterInfo = getimagesize($water);

        if (empty($water)) {
            throw new \Exception('非法水印文件');
        }

        // 打开水印图片资源
        $waterFun = 'imagecreatefrom' . image_type_to_extension($waterInfo[2], false);
        $waterIm = $waterFun($water);

        // 设定水印位置
        switch ($locate) {
            /* 右下角水印 */
            case self::WATER_SOUTHEAST:
                $x = $this->info['width'] - $waterInfo[0];
                $y = $this->info['height'] - $waterInfo[1];
                break;

            /* 左下角水印 */
            case self::WATER_SOUTHWEST:
                $x = 0;
                $y = $this->info['height'] - $waterInfo[1];
                break;

            /* 左上角水印 */
            case self::WATER_NORTHWEST:
                $x = $y = 0;
                break;

            /* 右上角水印 */
            case self::WATER_NORTHEAST:
                $x = $this->info['width'] - $waterInfo[0];
                $y = 0;
                break;

            /* 居中水印 */
            case self::WATER_CENTER:
                $x = ($this->info['width'] - $waterInfo[0]) / 2;
                $y = ($this->info['height'] - $waterInfo[1]) / 2;
                break;

            /* 下居中水印 */
            case self::WATER_SOUTH:
                $x = ($this->info['width'] - $waterInfo[0]) / 2;
                $y = $this->info['height'] - $waterInfo[1];
                break;

            /* 右居中水印 */
            case self::WATER_EAST:
                $x = $this->info['width'] - $waterInfo[0];
                $y = ($this->info['height'] - $waterInfo[1]) / 2;
                break;

            /* 上居中水印 */
            case self::WATER_NORTH:
                $x = ($this->info['width'] - $waterInfo[0]) / 2;
                $y = 0;
                break;

            /* 左居中水印 */
            case self::WATER_WEST:
                $x = 0;
                $y = ($this->info['height'] - $waterInfo[1]) / 2;
                break;

            default:
                throw new \Exception('不支持的水印位置类型');
        }

        // 放置水印图片到目标图片上
        imagecopy($this->im, $waterIm, $x, $y, 0, 0, $waterInfo[0], $waterInfo[1]);

        // 销毁水印图片资源
        imagedestroy($waterIm);

        return $this;
    }

    /**
     * 图像添加文字
     *
     * @param  string  $text   添加的文字
     * @param  string  $font   字体路径
     * @param  integer $size   字号
     * @param  string  $color  文字颜色
     * @param int      $locate 文字写入位置
     * @param  integer $offset 文字相对当前位置的偏移量
     * @param  integer $angle  文字倾斜角度
     *
     * @return $this
     * @throws \Exception
     */
    public function text($text, $font, $size, $color = '#00000000',
                         $locate = self::WATER_SOUTHEAST, $offset = 0, $angle = 0) {

        if (!is_file($font)) {
            throw new \Exception("不存在的字体文件：{$font}");
        }

        //获取文字信息
        $info = imagettfbbox($size, $angle, $font, $text);
        $minx = min($info[0], $info[2], $info[4], $info[6]);
        $maxx = max($info[0], $info[2], $info[4], $info[6]);
        $miny = min($info[1], $info[3], $info[5], $info[7]);
        $maxy = max($info[1], $info[3], $info[5], $info[7]);

        /* 计算文字初始坐标和尺寸 */
        $x = $minx;
        $y = abs($miny);
        $w = $maxx - $minx;
        $h = $maxy - $miny;

        /* 设定文字位置 */
        switch ($locate) {
            /* 右下角文字 */
            case self::WATER_SOUTHEAST:
                $x += $this->info['width'] - $w;
                $y += $this->info['height'] - $h;
                break;
            /* 左下角文字 */
            case self::WATER_SOUTHWEST:
                $y += $this->info['height'] - $h;
                break;
            /* 左上角文字 */
            case self::WATER_NORTHWEST:
                // 起始坐标即为左上角坐标，无需调整
                break;
            /* 右上角文字 */
            case self::WATER_NORTHEAST:
                $x += $this->info['width'] - $w;
                break;
            /* 居中文字 */
            case self::WATER_CENTER:
                $x += ($this->info['width'] - $w) / 2;
                $y += ($this->info['height'] - $h) / 2;
                break;
            /* 下居中文字 */
            case self::WATER_SOUTH:
                $x += ($this->info['width'] - $w) / 2;
                $y += $this->info['height'] - $h;
                break;
            /* 右居中文字 */
            case self::WATER_EAST:
                $x += $this->info['width'] - $w;
                $y += ($this->info['height'] - $h) / 2;
                break;
            /* 上居中文字 */
            case self::WATER_NORTH:
                $x += ($this->info['width'] - $w) / 2;
                break;
            /* 左居中文字 */
            case self::WATER_WEST:
                $y += ($this->info['height'] - $h) / 2;
                break;
            default:
                /* 自定义文字坐标 */
                if (is_array($locate)) {
                    list($posx, $posy) = $locate;
                    $x += $posx;
                    $y += $posy;
                } else {
                    throw new \Exception('不支持的文字位置类型');
                }
        }
        /* 设置偏移量 */
        if (is_array($offset)) {
            $offset = array_map('intval', $offset);
            list($ox, $oy) = $offset;
        } else {
            $offset = intval($offset);
            $ox     = $oy = $offset;
        }
        /* 设置颜色 */
        if (is_string($color) && 0 === strpos($color, '#')) {
            $color = str_split(substr($color, 1), 2);
            $color = array_map('hexdec', $color);
            if (empty($color[3]) || $color[3] > 127) {
                $color[3] = 0;
            }
        } elseif (!is_array($color)) {
            throw new \Exception('错误的颜色值');
        }

        /* 写入文字 */
        $col = imagecolorallocatealpha($this->im, $color[0], $color[1], $color[2], $color[3]);
        imagettftext($this->im, $size, $angle, $x + $ox, $y + $oy, $col, $font, $text);

        return $this;
    }

    /**
     * 返回图片信息
     * @return array
     */
    public function getImgInfo ()
    {
        return $this->info;
    }
}