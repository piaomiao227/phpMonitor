<?php
//�������ú���

function pr($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';

}


/**
 *  ��TP��C���� 2016-12-7
 */ 

function C($key='',$val=''){
    
    static  $config = array();
    if(empty($config)){
        require_once(ROOT_PATH.'/config.php');
        $config = $SysConfig;
    }
   
    if($key === ''){
        return $config;
    }
    if($val != ''){
        return $val;
    }else{
        return  !array_key_exists($key,$config) ? NULL : $config[$key];
    }
    
    
}

/**
 *  д��־
 *  2016-12-8
 */ 
function logWrite($logName, $msg, $type="info") {
	$logPath = LOG_PATH.$logName;
	//if(!is_dir($logPath)) mkdir($logPath,0777,true);
	$logMsg = "[$type]==".date('Y-m-d H:i:s')."==".$msg."\r\n";
	file_put_contents($logPath, $logMsg, FILE_APPEND);
	return true;
}


?>