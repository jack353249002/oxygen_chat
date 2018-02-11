<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 22:13
 */
$common_conf=include_once("E:\wamp64\www\oxygen_chat\Communication\Conf\common.php");
include_once($common_conf["workerman_url"]);
include_once($common_conf["Db"]);
use Workerman\Worker;
// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker($common_conf["socket_name"]);
$redis=new \Redis();
$redis->connect($common_conf["redis_server"],$common_conf["redis_port"]);
// 启动4个进程对外提供服务
$http_worker->count = 4;
$http_worker->onMessage = function($connection, $data)use($http_worker)
{
    $infor=json_decode($data,true);
    $house_id=$infor["house_id"];
    $token=$infor["token"];
    switch ($infor["type_id"])
    {
        /*进入房间加入数组*/
        case 1:
            $GLOBALS["{$house_id}"][]=$connection;
            $userinfor=getuser_in_reids($token); //获取用户信息
            $jsondata["type_id"]=1;
            $jsondata["data"]=$userinfor;
            send_information_all($house_id,json_encode($jsondata)); //发送用户信息给聊天室中的其他人
            break;
        /*发送消息*/
        case 2:
            $chat_infor=$infor["chat_infor"];
            send_information_all($house_id,$chat_infor);
            break;
    }

};

// 运行worker
Worker::runAll();
/*发送消息给指定房间的全局*/
function send_information_all($house_id,$data)
{
    foreach($GLOBALS["{$house_id}"] as $people)
    {
        $people->send($data);
    }
}
/*取出redis缓存*/
function getuser_in_reids($token)
{
    global $redis;
    return $redis->hGet('session',$token);
}