<?php

//程序入口文件
date_default_timezone_set ( 'PRC' );

define ( 'IS_CGI', (0 === strpos ( PHP_SAPI, 'cgi' ) || false !== strpos ( PHP_SAPI, 'fcgi' )) ? 1 : 0 );
define ( 'IS_WIN', strstr ( PHP_OS, 'WIN' ) ? 1 : 0 );
define ( 'IS_CLI', PHP_SAPI == 'cli' ? 1 : 0 );

define('ROOT_PATH',__DIR__);  //站点更目录
define('LOG_PATH',__DIR__.'/Log/'); //日志目录
define('APP_PATH',__DIR__.'/App/'); //监控任务目录
define('Lib_PATH',__DIR__.'/Library/'); //监控任务目录

//载入基本类
require_once(Lib_PATH.'Email.class.php');

//完成程序的初始化信息
include 'library/Common.php';
function __autoload($class_name){
	if(file_exists("App/".$class_name.'.php')) {
    	require_once "App/".$class_name.'.php';
	}else{
		if(file_exists("Model/".$class_name.'.php')) {
			require_once "Model/".$class_name.'.php';	
		}
	}
}       
            
@set_time_limit(0);
@ini_set('memory_limit','-1');


//自动运行程序中的任务
$appList = glob(APP_PATH . '*php');

foreach($appList as  $val){
    $basename = basename($val,'.php');
    $taskObj = new $basename();
    //只有开启监控，才能被执行
    if($taskObj->getSwitchStatus()){
         $taskObj->run();
    }
    
   
}



?>