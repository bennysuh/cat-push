<?php
use Workerman\Worker;
require_once __DIR__ . '/../workerman/Autoloader.php';
require_once __DIR__ . '/../channel/src/Client.php';

/**
 * 启动websocket服务
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-02
 */

// websocket服务端
$worker = new Worker('websocket://127.0.0.1:4236');
$worker->count = 1;
$worker->name = 'pusher';

//worker启动
$worker->onWorkerStart = function($worker) {
    // Channel客户端连接到Channel服务端
    Channel\Client::connect('127.0.0.1', 2206);
    // 以自己的进程id为事件名称
    $event_name = $worker->id;
    // 订阅worker->id事件并注册事件处理函数
    Channel\Client::on($event_name, function($event_data)use($worker){
        $to_connection_id = $event_data['to_connection_id'];
        $message = $event_data['content'];
        if(isset($worker->connections[$to_connection_id])) {
            echo "connection not exsits\n";
            return;
        }
        $to_connection = $worker->connections[$to_connection_id];
        $to_connection->send($message);
    });
    
    // 订阅广播事件
    $event_name = '广播';
    // 收到广播事件后向当前进程内所有客户端连接发送广播数据
    Channel\Client::on($event_name, function($event_data)use($worker) {
        $message = $event_data['content'];
        foreach ($worker->connections as $connection) {
            $connection->send($message);
        }
    });
};

//客户端连接
$worker->onConnect = function($connection)use($worker) {
    $server_msg = "CID:{$connection->id} connected server {$worker->id}\n";
    echo $server_msg;
    
    //向当前用户推送连接成功消息
    $client_msg = "恭喜您，成功连接到服务器。您的ID为：{$connection->id}";
    $conn_data = array(
        'type' => 'msg',
        'content' => $client_msg
    );
    $connection->send(json_encode($conn_data));
    
    //向所有用户推送当前在线用户数
    $num = count($worker->connections);
    $event_name = '广播';
    $online_data = array(
        'type' => 'online',
        'num' => $num
    );
    Channel\Client::publish($event_name, array(
       'content' => json_encode($online_data)
    ));
};

//断开连接
$worker->onClose = function($connection)use($worker) {
    $server_msg = "CID:{$connection->id} disconnect from server {$worker->id}\n";
    echo $server_msg;    
    
    //向所有用户推送当前在线用户数
    $num = count($worker->connections);
    $event_name = '广播';
    $online_data = array(
        'type' => 'online',
        'num' => $num
    );
    Channel\Client::publish($event_name, array(
       'content' => json_encode($online_data)
    ));
};

Worker::runAll();

