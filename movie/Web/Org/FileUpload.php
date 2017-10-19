<?php
//文件上传类

class  FileUpload
{
    private $upfile;    //文件上传信息
    private $path;      //上传保存路径
    private $typeList = array(); //允许上传类型
    private $maxSize = 0; //文件上传大小
    private $fileName; //上传后的文件名
    private $errinfo; //错误信息
    
    public function __construct($upfile)
    {
        $this->upfile = $upfile;
    }
    
    public function __set($param,$value)
    {
        $this->$param = $value;
    }
    
    public function __get($param)
    {
        return $this->$param;
    }
    
    //执行文件上传
    public function upload()
    {
        //处理路径
        $this->path = rtrim($this->path,"/")."/";
        
        return $this->checkError() && $this->checkTypeList() && $this->checkMaxSize() && $this->makeFileName() && $this->moveUpfile();
    }
    
    //上传错误信息判断
    private function checkError()
    {
        if($this->upfile['error']>0){
            switch($this->upfile['error']){
                case 1: $err = "上传文件大小超出php.ini配置文件限制"; break;
                case 2: $err = "上传文件大小超出表单隐藏限制"; break;
                case 3: $err = "文件只有部分被上传。"; break;
                case 4: $err = "没有文件被上传"; break;
                case 6: $err = "找不到临时文件夹"; break;
                case 7: $err = "文件写入失败"; break;
                default: $err = "未知错误！"; break;
            }
            $this->errinfo = $err;//封装错误信息
            return false;
        }
        return true;
    }
    
    //判断上传类型
    private function checkTypeList()
    {
        if(count($this->typeList)>0){
            if(!in_array($this->upfile['type'],$this->typeList)){
                $this->errinfo = "上传类型错误！";//封装错误信息
                return false;
            }
        }
        return true;
    }

    //4.上传大小判断
    private function checkMaxSize()
    {
        if($this->maxSize>0){
            if($this->upfile["size"]>$this->maxSize){
                $this->errinfo = "文件上传大小超出当前限制！";//封装错误信息
                return false;
            }
        }
        return true;
    }

    //随机上传文件名
    private function makeFileName()
    {
        $ext = pathinfo($this->upfile['name'],PATHINFO_EXTENSION); //获取上传文件的后缀名
        do{
           $this->fileName = time().rand(1000,9999).".".$ext;//随机一个新文件名
        }while(file_exists($this->path.$this->fileName)); //判断是否存在（重名）
        return true;
    }

    //执行移动上传文件
    private function moveUpfile()
    {
        if(is_uploaded_file($this->upfile['tmp_name'])){
            if(move_uploaded_file($this->upfile['tmp_name'],$this->path.$this->fileName)){
                return true;
            }else{
                $this->errinfo = "文件上传移动失败！";
            } 
        }else{
            $this->errinfo = "不是一个有效上传文件";
        }
        return false;
    }
}
