<?php
//网站前台home主入口

session_start();//开启

	function __autoload($cname)//自动加载类
	{
		error_reporting(E_ERROR);
		$fname = $cname.'.php';
		if(file_exists('./Web/Controller/Home/'.$fname)){
			require('./Web/Controller/Home/'.$fname);
		}elseif(file_exists('./Web/Model/'.$fname)){
			require('./Web/Model/'.$fname);
		}elseif(file_exists('Web/Org/'.$fname)){
			require('Web/Org/'.$fname);
		}else{
			die('<h2>错误！'.$fname.'类加载失败</h2>');
		}
	}
	//去两遍下划线，用/拆分成数组
	$pathinfo = @explode('/',trim($_SERVER['PATH_INFO'],'/'));

	//获取请求类名，没有默认index
	$className = ucfirst(!empty($pathinfo[0])?$pathinfo[0]:'index');
	//获取请求方法名，没有默认indexs
	$method = !empty($pathinfo[1])?$pathinfo[1]:'indexs';

	define('_PUBLIC_',dirname($_SERVER['SCRIPT_NAME']).'/Public/');
	//获取当前文件的路径
	define('URL',$_SERVER['SCRIPT_NAME']);
	//定义当前控制器类名
	define('CONTROLLER',$className);
	//定义当前控制器方法名
	define('METHOD',$method);

	//导入配置文件
	require('./Web/Conf/config.php');
	//导入公共函数库
	require('./Web/Common/functions.php');

	//实例化
	$controller = new $className();
	$controller->run($method);
