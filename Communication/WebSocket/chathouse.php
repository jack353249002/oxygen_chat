<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 22:13
 */
$common_conf=include_once("E:\wamp64\www\oxygen_chat\Communication\Conf\common.php");
include_once($common_conf["workerman_url"]);
use Workerman\Worker;
// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker($common_conf["socket_name"]);

// 启动4个进程对外提供服务
$http_worker->count = 4;

$http_worker->onMessage = function($connection, $data)use($http_worker)
{
   $infor=json_decode($data,true);
    $house_id=$infor["house_id"];
    switch ($infor["type_id"])
    {
        /*进入房间加入数组*/
        case 1:
            $GLOBALS["{$house_id}"][]=$connection;
            break;
        /*发送消息*/
        case 2:
            $chat_infor=$infor["chat_infor"];
            foreach($GLOBALS["{$house_id}"] as $people)
            {
                $people->send($chat_infor);
            }
    }

};

// 运行worker
Worker::runAll();