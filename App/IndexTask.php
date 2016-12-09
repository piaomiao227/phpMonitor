<?php
/**
 *  默认监控任务
 */ 

class IndexTask  extends BaseTask{
    
    public $turnOn = 1; //是否启用
  
    
    function __construct(){
        parent::__construct();
        
        
    }
    
    
  
  
    /**
     *  执行具体的任务
     */ 
    public function run(){
      
     
      //监控redis服务
      $redis_ret = $this->monitorRedis();
      
      //监控mysql服务
      $mysql_ret = $this->monitorMysql();
       
      logWrite(__CLASS__ . '.log','执行成功!!'.',Redis监控结果'.(int)$redis_ret.',Mysql监控结果'.(int)$mysql_ret);
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
        
        $monitorConfig = array('redis'=> array(                                 
                						"server"=>"192.168.201.76", 
                						"port"=>"6379"                       
                						),
                               'mysql'=>array( 'apollo_db' => array(                                 
                            						"server"=>"localhost", 
                            						"port"=>"3306",
                                                    "dbname"=>"zxcp",
                                                    "username"=>"root",
                                                    "password"=>"123456"                       
                            						),
                                              'apollo_db2' => array(                                 
                            						"server"=>"localhost", 
                            						"port"=>"3306",
                                                    "dbname"=>"zxcp",
                                                    "username"=>"root",
                                                    "password"=>"123456"                       
                            						),
                               
                               
                                            ), 
                        
                        );
        return $monitorConfig;
   }
   
   
  
   
   /**
    *  监控redis服务链接状态
    */ 
   private function monitorRedis(){
    
        $redis = new Redis();
        $config = $this->getTaskConfig();
        $redisConfig = $config['redis'];
        if(!$redis->connect($redisConfig['server'],$redisConfig['port'])) {
            //redis链接失败，发送邮件通知
           echo  $mail_body =  'redis服务 '.$redisConfig['server'].',端口:'.$redisConfig['port'].',连接失败！';
            $this->sendNoticeMail('Redis服务连接不上了',$mail_body); 
            return false;
        }else{
            $redis->close();
            return true;
        }
    
        
   }
   
   
   /**
    *  数据库状态监控
    */    
   private function monitorMysql(){
        $retStatus = true;
        $config = $this->getTaskConfig();
        $mysqlConfig = $config['mysql'];
        if(is_array($mysqlConfig)){
            foreach($mysqlConfig as $dbConfig){
                 $con = @mysqli_connect($dbConfig['server'],$dbConfig['username'],$dbConfig['password'],$dbConfig['dbname']);
                  if(!$con){
                     echo  $mail_body =  'mysql服务 '.$dbConfig['server'].',数据库名:'.$dbConfig['dbname'].',连接失败！'.mysqli_connect_error().',ERROR_NO:'. mysqli_connect_errno() ;
                     $this->sendNoticeMail('Mysql服务['.$dbConfig['server'].']连接不上了',$mail_body);
                     $retStatus = false; 
                  }else{
                      mysqli_close($con);
                  }
                
              
            }
        }
        
        return $retStatus;
    
   }
   
   
   
  
    
}


?>