<?php
/**
 *  基类
 */ 

class BaseTask {
    
    
    public $turnOn = 1; //是否启用
    public $dirLog; //日志目录路径
    public $config = array(); //监控的配置信息
  
    
    function __construct(){
        $this->dirLog = LOG_PATH;
    }
    
    
    /**
     *  获取启用状态
     */ 
    public function getSwitchStatus(){
        return $this->turnOn;
        
    }
    
    /**
     *  执行具体的任务
     */ 
    public function run(){
        
        

    }
   
   /**
    * 判断是否有执行条件
    */ 
   public function checkRun(){
        return false;
   }
   
    /**
    *  发送通知邮件
    * 
    */ 
   protected function sendNoticeMail($mail_subject,$mail_body,$mail_to=''){
         if(empty($mail_to)){
            $mail_to = C('mailto');
         }
         $mail_subject = '这是一个告警邮件 '.$mail_subject;
         $mail_body .= '<br>出错时间：'.date('Y-m-d H:i:s').'<br>服务器信息：'.var_export($_SERVER,true);
         $ret = Email::send($mail_to, $mail_subject, $mail_body);
         return $ret;
   }
   
   
    
    
    
}


?>