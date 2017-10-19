<?php
//控制器类的基类(父类)
class Controller
{
	public function __construct()
	{
		if(CONTROLLER == 'Users' && empty($_SESSION['indexuser']['username'])){
			header('Location:'.URL.'/login/logins');
			exit;
		}
		if(CONTROLLER == 'Order' && empty($_SESSION['indexuser']['username'])){
			header('Location:'.URL.'/login/logins');
			exit;
		}
	}
	//负责执行子类中的方法
	public function run($method)
	{
		//判断方法是否存在
		if(method_exists($this,$method)){
			$this->$method();//调用此方法
		}else{
			die("<h2>你调用的方法{$method}不存在！</h2>");
		}
	}
	//加载模板方法
	public function display($tpl)
	{
		$cname = CONTROLLER;//$cname = get_class($this);//返回对象的类名
		require("./Web/View/Home/{$cname}/".$tpl.".html");
	}
}