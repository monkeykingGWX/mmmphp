<?php
/**
 * 验证码类
 */
namespace mmmphp\lib;

class Verify
{

    /**
     * 配置项
     * @var array
     */
    private $config = [
        'seKey'    => 'mmmphp',                  // 加密密钥
        'imageW'   => 0,                             // 图片宽度
        'imageH'   => 0,                             // 图片高度
        'bgColor'  => [],                           // 图片背景色
        'font'     => 0,                               // 字体
        'len'      => 4,                                // 验证码位数
        'fontSize' => 25,                         // 字体大小
        'useZh'    => false,                         // 使用中文验证码
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',             // 验证码字符集合
        'zhSet'    => '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借',              // 中文验证码字符串
        'expire'   => 1800,     // 验证码过期时间
        'reset'    => true,     // 验证成功后是否重置
    ];

    /**
     * 图片资源句柄
     * @var null
     */
    private $im = null;

    /**
     *  验证码字符
     * @var ''
     */
    private $code = '';

    /**
     * 架构方法 设置参数
     * @access public
     * @param  array $config 配置参数
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }


    /**
     * 设置验证码配置
     * @access public
     * @param  string $name 配置名称
     * @param  string $value 配置值
     * @return void
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * 使用 $this->name 获取配置
     * @access public
     * @param  string $name 配置名称
     * @return string    配置值
     */
    public function __get($name)
    {
        return $this->config[$name];
    }

    /**
     * 检查配置
     * @access public
     * @param  string $name 配置名称
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * 输出验证码并把验证码的值保存的session中
     * 验证码保存到session的格式为： array('code' => '验证码值', 'time' => '验证码创建时间');
     * @return void
     */
    public function entry()
    {
        // 图片宽(px)
        $this->imageW || $this->imageW = $this->len * $this->fontSize * 1.5 + $this->len * $this->fontSize / 2;
        // 图片高(px)
        $this->imageH || $this->imageH = $this->fontSize * 2.5;
        // 建立一幅 $this->imageW x $this->imageH 的图像
        $this->im = imagecreatetruecolor($this->imageW, $this->imageH);
        // 背景色
        if (empty($this->bgColor)) {
            $bgColor = imagecolorallocate($this->im, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        } else {
            $bgColor = imagecolorallocate($this->im, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
        }

        // 背景填充
        imagefill($this->im, 0, 0, $bgColor);

        // 绘制验证码
        $this->createCode();

        // 绘制杂点
        $this->writeNoise();

        // 将验证码字符保存在session中
        $verify = [
            'code' => $this->authCode(strtolower($this->code)),
            'time' => time()
        ];
        Session::set($this->authCode($this->seKey), $verify);

        // 图片输出
        header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header("Content-Type:image/png");

        imagepng($this->im);
        imagedestroy($this->im);
    }

    /**
     * 校验验证码是否正确
     * @param $code 验证码
     * @return bool
     */
    public function check($code)
    {
        $key    = $this->authCode($this->seKey);
        $secode = Session::get($key);

        if (empty($code) || empty($secode)) {
            return false;
        }

        if (time() - $secode['time'] > $this->expire) {
            unset($_SESSION[$key]);
            return false;
        }

        if ($secode['code'] != $this->authCode(strtolower($code))) {
            return false;
        }

        // 校验成功
        if ($this->reset) {
            unset($_SESSION[$key]);
        }

        return true;
    }

    /**
     * 向图像中绘制验证码
     */
    private function createCode()
    {
        // 随机验证码字体颜色
        $textColor = imagecolorallocate($this->im, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));
        // 随机验证码字体
        $font = $this->font ?
            $font = __DIR__ . '/verify/ttf/' . $this->font . '.ttf' :
            $font = __DIR__ . '/verify/ttf/' . mt_rand(1, 5) . '.ttf';

        $code = []; // 验证码
        $codeNX = 0; // 验证码第N个字符的左边距
        if ($this->useZh) {
            // 使用中文验证码
            for ($i = 0; $i < $this->len; $i++) {

                $code[$i] = mb_substr(
                    $this->zhSet,
                    mt_rand(0, mb_strlen($this->zhSet, 'utf-8')),
                    1,
                    'utf-8'
                );

                imagettftext(
                    $this->im,
                    $this->fontSize,
                    mt_rand(-40, 40),
                    $this->fontSize * ($i + 1) * 1.5,
                    $this->fontSize + mt_rand(10, 20),
                    $textColor,
                    $font,
                    $code[$i]
                );
            }
        } else {
            for ($i = 0; $i < $this->len; $i++) {
                $code[$i] = $this->codeSet[mt_rand(0, strlen($this->codeSet) - 1)];
                $codeNX += mt_rand($this->fontSize * 1.2, $this->fontSize * 1.6);
                imagettftext($this->im, $this->fontSize, mt_rand(-40, 40), $codeNX, $this->fontSize * 1.6, $textColor, $font, $code[$i]);
            }
        }

        $this->code = implode('',$code);
    }

    /**
     * 绘制干扰元素
     */
    private function writeNoise ()
    {
        // 绘制干扰点
        for ($i =0; $i < 100; $i++) {
            // 随机色
            $color = imagecolorallocate($this->im, mt_rand(200,225), mt_rand(200,225), mt_rand(200,225));
            imagesetpixel($this->im, mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), $color);
        }

        // 绘制干扰线
        for ($i =0; $i < 10; $i++) {
            // 随机色
            $color = imagecolorallocate($this->im, mt_rand(200,225), mt_rand(200,225), mt_rand(200,225));
            imageline($this->im, mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), mt_rand(0, $this->imageW), mt_rand(0, $this->imageH),$color);
        }

        // 干扰字符
        for ($i = 0; $i < 5; $i++) {
            // 随机色
            $color = imagecolorallocate($this->im, mt_rand(175,200), mt_rand(175,200), mt_rand(175,200));
            imagestring($this->im, 5, mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), $this->codeSet[mt_rand(0, strlen($this->codeSet) -1)], $color);
        }
    }

    /**
     * 验证码字符加密
     * @param $str string 待加密的字符
     * @return string 加密后字符
     */
    private function authCode ($str)
    {
        $key = substr(md5($this->seKey), 5, 8);
        $str = substr(md5($str), 8, 10);

        return md5($key . $str);
    }
}