<?php
/**
 *  站点监控任务
 *   每5分钟监听一次
 */ 

class SiteTask  extends BaseTask{
    
    public $turnOn = 1; //是否启用
  
    
    function __construct(){
        parent::__construct();
        
        
    }
    
    
  
  
    /**
     *  执行具体的任务
     */ 
    public function run(){
      
     
      $ret = $this->monitorWebUrl();
       
      logWrite(__CLASS__ . '.log','执行成功!!'.',站点监控结果'.(int)$ret);
      
    }
   
   /**
    * 判断是否有执行条件
    */ 
   public function checkRun(){
        //如果没有启用，不予执行
        if(!$this->turnOn){
            return false;
        }
        
        $datetime = getdate();
        $minute  = $datetime['minutes'];
        //每5分钟执行
        if ($minute % 5 == 0){  
            return true;
        }
        
        return false;

    
   }
   
   
   /**
    *  获取配置信息
    */ 
   public function getTaskConfig(){
    
        //支持多个站点多个页面
        $monitorConfig = array('index' => array(                                 
                    						"url"=>"http://ugc.gs.baofeng.com/", 
                    						"port"=>"80",
                                            "pageName"=>"index",
                                            "siteName"=>"UGC首页",
                                                 
                    						),
                                            
                
                        
                        );
        return $monitorConfig;
   }
   
   
  
   
   /**
    *  通过定期调用curl方法，判断http 返回码来判断服务是否运行
    */ 
   public function monitorWebUrl(){
        $retStatus = true;
        $config = $this->getTaskConfig();
        if(is_array($config)){
            foreach($config as $val_config){
                 $url = $val_config['url'];
                 $http_code = $this->curlGetStatus($url);
                
                 if($http_code != 200){
                     //站点出现异常，发送邮件通知
                     echo  $mail_body =  '站点 '.$val_config['siteName'].',URL:'.$val_config['url'].',打开异常！！'.',http_code:'. $http_code ;
                     $this->sendNoticeMail('UGC网站首页['.$val_config['url'].']打开异常',$mail_body);
                     $retStatus = false;
                 }
            }
            
        }
        return $retStatus;
       
    
   }
   
    /**
    *  获取页面状态
    *  @return bool true为可用，false为不可用
    */ 
    private function curlGetStatus($url){
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);   //设置5S超时
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //echo $output;
        $curl_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //释放curl句柄
        curl_close($ch);
        return $curl_code;
        
   }
   

}


?>