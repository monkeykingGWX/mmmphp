<?php
/**
 * 分页类
 */

namespace mmmphp\lib;

class Page
{
    // 分页参数名
    private $pageParam = 'mmmphp_page';

    // 当前页码
    private $page = 0;

    // 总页码
    private $pageNum = 0;

    // 每页显示条数
    private $listRow = 0;

    // 总结果集数
    private $total = 0;

    // 每页显示几个分页
    private $listPage = 9;

    // 给sql的limit子句
    private $limit = '';

    // url路径
    private $uri = '';

    private $config = [
        "header" => "共%TOTAL%条记录",
        "prev" => "上一页",
        "next" => "下一页",
        "first" => "首页",
        "last" => "尾页",
        "current" => "%PAGE%/%PAGE_NUM%页",
        "theme" => "%HEADER% %FIRST% %PREV% %PAGE_LINK% %NEXT% %LAST% %CURRENT%"
    ];

    public function __construct(int $total, int $listRow = 25, $query = '')
    {
        $this->total   = $total;
        $this->listRow = Conf::get('PAGE_LIST_ROW')? : $listRow;
        $this->pageNum = ceil($total / $listRow);

        Conf::get('PAGE_PARAM') && $this->pageParam = Conf::get('PAGE_PARAM');
        Conf::get('PAGE_LIST_NUM') && $this->listPage = Conf::get('PAGE_LIST_NUM');

        // 获取当前页码
        if (isset($_GET[$this->pageParam])) {
            $page = (int) $_GET[$this->pageParam];

            if ($page < 1 || $page > $this->pageNum) {
                $this->page = 1;
            } else {
                $this->page = $page;
            }
        } else {
            $this->page = 1;
        }

        $this->limit = "LIMIT " . ($this->page-1) * $listRow . ",$listRow";
        $this->uri = $this->getUri($query);
    }

    public function __get ($property)
    {
        if (isset($this->$property)) {
            return $this->$property;
        } else {
            return null;
        }
    }

    public function __set ($property, $val)
    {
        if (isset($this->$property)) {
            $this->$property = $val;
        }
    }

    /**
     * 定制分页设置
     * @param array $config
     */
    public function setConfig (array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    private function getUri ($query)
    {
        $queryUrl      = $_SERVER['REQUEST_URI'];

        if (strstr($queryUrl, '?')) {
            if (substr($queryUrl, 0, -1) != '?') {
                $url = $queryUrl . '&';
            }
        } else {
            $url = $queryUrl . '?';
        }
        // 将query拼接上
        if (is_array($query)) {
            $url .=  http_build_query($query);
        } else {
            $url .= trim($query, '&?');
        }

        // 再将所有query解析出,过滤掉重复的query以及分页的query
        $urlArr = parse_url($url);

        if (isset($urlArr['query'])) {
            parse_str($urlArr['query'], $queryArr);
            unset($queryArr[$this->pageParam]);
            $url = $urlArr['path'] . '?' . http_build_query($queryArr);
        }

        if (strstr($url, '?')) {
            $url = substr($url, -1) == '?' ? $url : ($url . '&');
        } else {
            $url .= '?';
        }

        return $url;
    }

    public function show ()
    {
        // 总共只有一页
        if ($this->pageNum <= 1) {
            return '';
        }

        $pageUrl = $this->uri . $this->pageParam . '=';

        // header current
        $header  = '<li class="page-item header">' . $this->config['header'] . '</li>';
        $current = '<li class="page-item current">' . $this->config['current'] . '</li>';

        // 上一页
        if ($this->page > 1) {
            $prevUrl = $pageUrl . ($this->page - 1);
            $prev = '<li class="page-item page-up"><a class="page-link" href="' . $prevUrl . '">' . $this->config['prev']  . '</a></li>';
        } else {
            $prev = '';
        }

        // 下一页
        if ($this->page < $this->pageNum) {
            $nextUrl = $pageUrl . ($this->page + 1);
            $next = '<li class="page-item page-down"><a class="page-link" href="' . $nextUrl . '">' . $this->config['next']  . '</a></li>';
        } else {
            $next = '';
        }

        // 首页
        if ($this->page <= 1) {
            $first = '<li class="page-item page-first disabled"><a class="page-link" href="javascript:;">' . $this->config['first'] . '</a></li>';
        } else {
            $firstUrl = $pageUrl . '1';
            $first = '<li class="page-item page-first"><a class="page-link" href="' . $firstUrl . '">' . $this->config['first'] . '</a></li>';
        }

        // 尾页
        if ($this->page >= $this->pageNum) {
            $last = '<li class="page-item page-last disabled"><a class="page-link" href="javascript:;">' . $this->config['last'] . '</a></li>';
        } else {
            $lastUrl = $pageUrl . $this->pageNum;
            $last = '<li class="page-item page-last"><a class="page-link" href="' . $lastUrl . '">' . $this->config['last'] . '</a></li>';
        }

        // pagelink
        $asideNum = floor($this->listPage / 2);
        $linkPage = '';

        for ($page = $this->page - $asideNum; $page <= $this->page; $page++) {
            if ($page < 1) {
                continue;
            }

            $linkUrl   = $pageUrl . $page;
            $linkPage .= '<li class="page-item"><a class="page-link" href="' . $linkUrl . '">' . $page . '</a></li>';
        }

        for ($page = $this->page + 1; $page <= $this->page + $asideNum; $page++) {
            if ($page > $this->pageNum) {
                break;
            }

            $linkUrl   = $pageUrl . $page;
            $linkPage .= '<li class="page-item"><a class="page-link" href="' . $linkUrl . '">' . $page . '</a></li>';
        }

        $pageStr = str_replace(
            [ '%HEADER%', '%PREV%', '%FIRST%', '%PAGE_LINK%', '%LAST%', '%NEXT%', '%CURRENT%', '%TOTAL%', '%PAGE%', '%PAGE_NUM%'],
            [ $header, $prev, $first, $linkPage, $last, $next, $current, $this->total, $this->page, $this->pageNum],
            $this->config['theme']
        );
        $style = "<style>
    .mmmphp-page{text-align: center;margin:16px 0;padding:0}
    .mmmphp-page li{list-style: none;display: inline-block;padding:0 3px;}
    .mmmphp-page li a{text-decoration: none;color:#006699}
    .mmmphp-page li.disabled a{color:#000}
</style> ";
        return $style.PHP_EOL.'<ul class="mmmphp-page">' . $pageStr . '</ul>';
    }
}