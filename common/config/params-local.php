<?php
return [
    //手机短信验证API地址
//    'SMS_API'                => 'http://api.app2e.com/smsBigSend.api.php',
//    'SMS_API_USER'           => 'yilulao',
//    'SMS_API_PWD'            => 'LHXNGBey',
    'API_ACCOUNT'            => 'N2532727', // 创蓝API账号
    'API_PASSWORD'           => 'y5iEqHIbW7d119',// 创蓝API密
    'API_SEND_URL'           => 'http://smssh1.253.com/msg/send/json', //创蓝发送短信接口URL
	'API_VARIABLE_URL'       => 'http://smssh1.253.com/msg/variable/json',//创蓝变量短信接口URL
	'API_BALANCE_QUERY_URL'  => 'http://XXXX/msg/balance/json',//创蓝短信余额查询接口URL
    'recever' =>'1340747350@qq.com',

    'pushScreenQueue'=>'tcp://118.89.236.91:61613',
    'pushScreenQueueKey'=>'test_device_online_status',
//    'pushProgram'=>'http://123.207.145.129:8080',//推送节目单 测试环境
    'pushProgram'=>'http://api.admin.bjyltf.com/',//推送节目单 正式环境
];
