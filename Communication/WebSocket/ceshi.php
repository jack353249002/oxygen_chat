<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 22:03
 */
use Workerman\Worker;
require_once 'E:\wamp64\www\oxygen_chat\workerman\Autoloader.php';
// 注意：这里与上个例子不通，使用的是websocket协议
$ws_worker = new Worker("websocket://0.0.0.0:2000");

// 启动4个进程对外提供服务
$ws_worker->count = 1;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $connection->send('hello ' . $data);
};

// 运行worker
Worker::runAll();