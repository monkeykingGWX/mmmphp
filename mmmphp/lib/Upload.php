<?php
/**
 * 文件上传类
 * Class Upload
 */
namespace mmmphp\lib;


class Upload
{
    /**
     * @var array 可设置项
     */
    private $configs = [
        'allow_type' => [],     // 允许上传的文件后缀
        'max_size' => 0,        // 限制文件大小
        'upload_path' => './upload',    // 文件上传根目录
        'sub_path' => ''        // 子目录
    ];

    /**
     * @var string 文件后缀
     */
    private $fileType = '';

    /**
     * @var string 上传成功文件的路径
     */
    private $newFilePath = '';

    /**
     * @var array 错误信息
     */
    private $errs = [
        1 => '未找到上传文件',
        2 => '文件太大',
        3 => '文件部分上传',
        4 => '没有上传文件',
        5 => '文件类型不合法',
        6 => '根目录创建失败',
        7 => '子目录上传失败',
        8 => '非http上传',
        9 => '文件上传失败'
    ];

    /**
     * @var int 错误码
     */
    private $errCode = 0;

    /**
     * Upload constructor.
     * @param array $configs 初始化设置
     */
    public function __construct ($configs = [])
    {
        if (!array_key_exists('sub_path', $configs)) {
            $this->configs['sub_path'] = date('Ymd', time());
        }

        foreach ($configs as $property => $val) {
            if (array_key_exists($property, $this->configs)) {
                if ($property == 'upload_path' || $property == 'sub_path') {
                    $val = rtrim($val, '/\\');
                }
                $this->configs[$property] = $val;
            }
        }
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getErrMsg ()
    {
        return $this->errs[$this->errCode];
    }

    /**
     * 获取上传文件路径
     * @return string
     */
    public function getFilePath ()
    {
        return $this->newFilePath;
    }

    /**
     * 上传文件
     * @param string $fieldName $_FILES[$file]的中$file值
     * @return bool;
     */
    public function upload ($fieldName = 'file')
    {
        if (empty($_FILES[$fieldName])) {
            $this->errCode = 1;
            return false;
        }

        $fileType = strstr( $_FILES[$fieldName]['type'], '/');
        $this->fileType = trim($fileType, '/');

        // 文件上传错误校验
        if (!$this->checkErr($_FILES[$fieldName]['error'])) {
            return false;
        }

        // 文件大小校验
        if (!$this->checkSize($_FILES[$fieldName]['size'])) {
            return false;
        }

        // 文件后缀校验
        if (!$this->checkType($this->fileType)) {
            return false;
        }

        // 上传文件
        if (!$this->uploadOne($_FILES[$fieldName]['tmp_name'], $_FILES[$fieldName]['name'])) {
            return false;
        }

        // 上传成功
        return true;
    }

    /**
     * 批量上传文件
     * @param string $fieldName
     * @return bool
     */
    public function uploadSomeFile ($fieldName = '') {
        if (empty($_FILES[$fieldName])) {
            $this->errCode = 1;
            return false;
        }

        foreach ($_FILES[$fieldName]['tmp_name'] as $k => $val) {
            // 文件上传错误校验
            if (!$this->checkErr($_FILES[$fieldName]['error'][$k])) {
                return false;
            }

            // 文件大小校验
            if (!$this->checkSize($_FILES[$fieldName]['size'][$k])) {
                return false;
            }

            // 文件后缀校验
            $fileType = strstr( $_FILES[$fieldName]['type'][$k], '/');
            $this->fileType = trim($fileType, '/');

            if (!$this->checkType($this->fileType)) {
                return false;
            }
        }

        foreach ($_FILES[$fieldName]['tmp_name'] as $k => $val) {
            // 上传文件
            if (!$this->uploadOne($val, $_FILES[$fieldName]['name'][$k])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 检验上传错误
     * @param $error
     * @return bool
     */
    private function checkErr ($error)
    {
        switch ($error) {
            case 1:
            case 2:
                $this->errCode = 2;
                break;
            case 3:
                $this->errCode = 3;
                break;
            case 4:
                $this->errCode = 4;
                break;
        }

        if ( $error== 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 校验文件大小
     * @param $fileSize
     * @return bool
     */
    private function checkSize ($fileSize)
    {
        if ($fileSize > $this->configs['max_size']) {
            $this->errCode = 2;
            return false;
        } else {
            return true;
        }
    }

    /**
     * 校验文件类型
     * @param $allowType
     * @return bool
     */
    private function checkType ($allowType)
    {
        if (!in_array($allowType, $this->configs['allow_type'])) {
            $this->errCode = 5;
            return false;
        } else {
            return true;
        }
    }

    /**
     * 上传文件
     * @param $tmpFile
     * @return bool
     */
    private function uploadOne ($tmpFile, $oldName)
    {
        // 创建跟目录及子目录
        if (!is_dir($this->configs['upload_path']) && !mkdir($this->configs['upload_path'], 0755)) {
            $this->errCode = 6;
            return false;
        }

        $subDir = $this->configs['upload_path'] . '/' . $this->configs['sub_path'];

        if (!is_dir($subDir) && !mkdir($subDir, 0755)) {
            $this->errCode = 7;
            return false;
        }

        // 是否为正常的http上传
        if (!is_uploaded_file($tmpFile)) {
            $this->errCode = 8;
            return false;
        }

        // 移动文件
        $newFileName = date('YmdHis') . mt_rand(100000,999999) . strrchr($oldName, '.');
        $newFilePath = $this->configs['upload_path'] . '/' . $this->configs['sub_path'] . '/' . $newFileName;

        if (!move_uploaded_file($tmpFile, $newFilePath)) {
            $this->errCode = 9;
            return false;
        }

        $this->newFilePath = $newFilePath;

        return true;
    }

}

