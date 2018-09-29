<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace COM;

class NewsPage{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage   = 5;// 分页栏每页显示的页数
	public $lastSuffix = true; // 最后一页是否显示总页数

    private $p       = 'p'; //分页参数名
    private $url     = ''; //当前链接URL
    private $nowPage = 1;

	// 分页显示定制
    private $config  = array(
        'header' => '<span><span class="rows">共 %TOTAL_ROW% 条记录</span></span>',
        'prev'   => '<',
        'next'   => '下一页>',
        'first'  => '<span>1</span>',
        'last'   => '<span>%TOTAL_PAGE%</span>',
        'theme'  => '%FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %UP_PAGE%',
    );

    /**
     * 架构函数
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows, $listRows, $parameter = array()) {
        C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
        /* 基础设置 */
        $this->totalRows  = $totalRows; //设置总记录数
        $this->listRows   = $listRows;  //设置每页显示行数
        $this->parameter  = empty($parameter) ? $_GET : $parameter;
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    /**
     * 组装分页链接
     * @return string
     */
    public function newsshow() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ? '<a class="last ppage" title="上一页" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ? '<a class="next ppage" title="下一页" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = '<span><a class="first num" href="' . $this->url(1) . '">' . $this->config['first'] . '</a></span>';

        }

        

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<span><a class="num" href="' . $this->url($page) . '">' . $page . '</a></span>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    if($page == 1){
                        $link_page .= '<span><a class="last color num" href="javascript:;" title="已经是第一页了！">1</a></span>';
                        $up_page = '<a class="last ppage" href="javascript:;" title="已经是第一页了！"><</a>';

                    }else if($page == $this->totalPages){
                        $link_page .= '<span><a class="next color num" href="javascript:;" title="已经是最后一页了！">'.$this->config['last'].'</a></span>';
                        $down_page = '<a href="javascript:;" title="已经是最后一页了！" class="last ppage">下一页></a>';
                    }else{
                        $link_page .= '<span><a class="next color num" href="javascript:;" title="'.$page.'">'.$page.'</a></span>';
                    }                    
                }
            }
        }
        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<span><a class="end num" href="' . $this->url($this->totalPages) . '">...' . $this->config['last'] . '</a></span><input id="pagee" value=""/>';

        }else{
            
            $link_page .= '<input id="pagee" value=""/>';
        }
        
        //替换分页内容
        $page_str = str_replace(
            array('%NOW_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%','%UP_PAGE%',  '%DOWN_PAGE%'),
            array($this->nowPage, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages,$up_page,  $down_page),
            $this->config['theme']);
        return "{$page_str}";
    }
}
