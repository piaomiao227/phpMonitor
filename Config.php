<?php
/** 
 *  配置信息
 *  2016-12-8 
 */ 
 
 $SysConfig = array(
     /**
      *  配置发送邮件
      */ 
    'mailConfig' =>  array('SMTP_HOST'=> 'mail.baofeng.com',
                           'SMTP_PORT'=> '25',
                           'SMTP_SSL'=> false,
                           'SMTP_USERNAME'=> 'fuxiangyu@baofeng.com',
                           'SMTP_PASSWORD'=> 'your mail password', //你的发送邮件的密码
                           'SMTP_FROM_TO'=> 'fuxiangyu@baofeng.com',
                           'SMTP_FROM_NAME'=> 'phpMonitor',
                          ),
                          
     //告警邮件发送地址                     
    'mailto'  => array('fuxiangyu@baofeng.com'),                
 
 );
 

?>
