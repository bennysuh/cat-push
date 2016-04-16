<?php
return array(
    //'配置项'=>'配置值'
    'LOAD_EXT_CONFIG' => 'menu_list',//加载配置
    
    //服务列表
    'SERVER_LIST' => array(
    		'registerServer' => array(
    				'name' => 'Register服务',
    				'path' => 'D:\workspace\cat-push\push\Applications\YourApp\start_register.php',
    				'ip' => '127.0.0.1',
    				'port' => '1238',
    				'protocol' => 'text'
    		),
    		'businessServer' => array(
    				'name' => 'PushBusinessWorker服务',
    				'path' => 'D:\workspace\cat-push\push\Applications\YourApp\start_businessworker.php',
    				'ip' => '',
    				'port' => '',
    				'protocol' => ''
    		),
    		'websocketServer' => array(
    				'name' => 'WebPushGateway服务',
    				'path' => 'D:\workspace\cat-push\push\Applications\YourApp\start_websocket_gateway.php',
    				'ip' => '127.0.0.1',
    				'port' => '8383',
    				'protocol' => 'websocket'
    		),
    		'RegisterServer' => array(
    				'name' => 'AppPushGateway服务',
    				'path' => 'D:\workspace\cat-push\push\Applications\YourApp\start_gateway.php',
    				'ip' => '127.0.0.1',
    				'port' => '8282',
    				'protocol' => 'text'
    		),
    ),
);