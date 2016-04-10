<?php
use Workerman\Worker;
require_once __DIR__ . '/../workerman/Autoloader.php';
require_once __DIR__ . '/../channel/src/Client.php';

/**
 * 启动http服务
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-02
 */

// 用来处理http请求，向任意客户端推送数据，需要传workerID和connectionID
$http_worker = new Worker('http://127.0.0.1:4237');
$http_worker->name = 'publisher';
$http_worker->onWorkerStart = function() {
    Channel\Client::connect('127.0.0.1', 2206);
};

//接收消息
$http_worker->onMessage = function($connection, $data) {
    $connection->send('ok');
    if(empty($_GET['content'])) return;
    // 是向某个worker进程中某个连接推送数据
    if(isset($_GET['to_worker_id']) && isset($_GET['to_connection_id'])) {
        $event_name = $_GET['to_worker_id'];
        $to_connection_id = $_GET['to_connection_id'];
        $content = $_GET['content'];
        Channel\Client::publish($event_name, array(
            'to_connection_id' => $to_connection_id,
            'content' => $content
        ));
    } else {
        //全局广播数据
        $event_name = '广播';
        $content = $_GET['content'];
        Channel\Client::publish($event_name, array(
           'content' => $content 
        ));
    }
};

Worker::runAll();

