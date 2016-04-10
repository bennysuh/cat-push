<?php
use Workerman\Worker;
require_once __DIR__ . '/../workerman/Autoloader.php';
require_once __DIR__ . '/../channel/src/Server.php';

/**
 * 启动一个channel服务
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016-04-02
 */

// 初始化一个Channel服务端
$channel_server = new Channel\Server('127.0.0.1', 2206);

Worker::runAll();

