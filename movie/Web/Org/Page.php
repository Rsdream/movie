<?php
//自定义分页类

class Page
{
    public $page=1;     //当前页
    public $pageSize=0; //页大小
    public $maxRows=0;  //最大数据条数
    public $maxPage=0;  //总页数
    
    public function __construct($maxRows,$pageSize=10)
    {
        $this->maxRows = $maxRows;
        $this->pageSize = $pageSize;
        //获取当前页
        $this->page = !empty($_GET['p'])?$_GET['p']:1;
        //计算总页数
        $this->getMaxPage();
        //判断是否页数越界
        $this->checkPage();
        
    }
    
    //计算总页数
    private function getMaxPage()
    {
        $this->maxPage = ceil($this->maxRows/$this->pageSize);
    }    
    
    //验证页数是否越界
    private function checkPage()
    {
        if($this->page > $this->maxPage){
            $this->page = $this->maxPage;
        }
        if($this->page < 1){
            $this->page = 1;
        }
    }
    
    //计算并返回分页SQL语句中的limit子句代码
    public function limit()
    {
        return (($this->page-1)*$this->pageSize).",".$this->pageSize;
    }
    
    public function show()
    {   
        $url = $_SERVER['PHP_SELF']; //获取当前页的url访问地址
        //获取并维持原有的搜索条件
        $param="";
        foreach($_GET as $k=>$v){
            //判断排除一下
            if($k!="p" && $v!==''){
                $param .= "&{$k}={$v}"; //拼装搜索条件
            }
        }
        //拼装页码信息
        $str  = " 当前第{$this->page}/{$this->maxPage}页  共计{$this->maxRows}条 ";
        $str .= " <a href='{$url}?p=1{$param}'>首页</a> ";
        $str .= " <a href='{$url}?p=".($this->page-1)."{$param}'>上一页</a> ";
        $str .= " <a href='{$url}?p=".($this->page+1)."{$param}'>下一页</a> ";
        $str .= " <a href='{$url}?p={$this->maxPage}{$param}'>尾页</a> ";
        //返回结果
        return $str;
    }
    
}

