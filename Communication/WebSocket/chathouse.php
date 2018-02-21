<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 22:13
 */
$common_conf=include_once("E:\wamp64\www\oxygen_chat\Communication\Conf\common.php");
require_once($common_conf["workerman_url"]);
require_once($common_conf["Db"]);
use Workerman\Worker;
use Workerman\Lib\Timer;
define('HEARTBEAT_TIME', 25);
// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker($common_conf["socket_name"]);
$redis=new \Redis();
$redis->connect($common_conf["redis_server"],$common_conf["redis_port"]);
// 启动4个进程对外提供服务
$http_worker->count = 1;
$http_worker->onWorkerStart = function($worker)
{
    // 将db实例存储在全局变量中(也可以存储在某类的静态成员中)
    global $db;
    global $common_conf;
    $db = new Workerman\MySQL\Connection($common_conf["DB_host"],$common_conf["DB_port"], $common_conf["DB_user"], $common_conf["DB_password"], $common_conf["DB_name"]);
    Timer::add(1, function()use($worker){
        $time_now = time();
        foreach($worker->connections as $connection) {
            // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
            if (empty($connection->lastMessageTime)) {
                $connection->lastMessageTime = $time_now;
                continue;
            }
            // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
            if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                $connection->close();
            }
        }
    });
};
$http_worker->onConnect=function($connection) use($http_worker)
{
   $GLOBALS["server_id"]["{$connection->id}"]=$connection;
};
$http_worker->onMessage = function($connection, $data)use($http_worker,$common_conf)
{
    try {
        /*心跳检测*/
        $connection->lastMessageTime = time();
        $infor = json_decode($data, true);
        $house_id = $infor["house_id"];
        $token = $infor["token"];
        global $db;
        switch ($infor["type_id"]) {
            /*进入房间加入数组*/
            case 1:
                $connection->token = $token;
                $connection->house_id = $house_id;
                $GLOBALS["house"]["{$house_id}"][] = $connection;
                $userinfor = json_decode(getuser_in_reids($token), true); //获取用户信息
                $userinfor["headportrait"] = $common_conf["Root_URL"] . $userinfor["headportrait"];
                $userinfor["server_id"] = $connection->id;
                $jsondata["type_id"] = 1;
                $jsondata["data"] = $userinfor;
                send_information_removeme($house_id, json_encode($jsondata), $connection->id);
                getnow_house_people($house_id, $connection->id);
                break;
            /*发送消息*/
            case 2:
                $sendinfor["type_id"] = 3;
                $chat_infor["chat_infor"] = $infor["chat_infor"];
                $chat_infor["user_infor"] = json_decode(getuser_in_reids($token), true); //获取用户信息
                $chat_infor["user_infor"]["headportrait"] = $common_conf["Root_URL"] . $chat_infor["user_infor"]["headportrait"];
                $sendinfor["data"] = $chat_infor;
                send_information_all($house_id, json_encode($sendinfor));
                $insert["houseid"] = $house_id;
                $insert["userid"] = $chat_infor["user_infor"]["id"];
                $insert["body"] = $infor["chat_infor"];
                $insert_id = $db->insert("content")->cols($insert)->query();
                break;
        }
    }
    catch (Exception $e) {

    }
};
$http_worker->onClose = function($connection) use($http_worker)
{
    try {
        $data["type_id"] = 0;
        $data["data"] = $connection->id;
        $house_id = $connection->house_id;
        send_information_all($house_id, json_encode($data));
        /*删除信息*/
        foreach ($GLOBALS["house"]["{$house_id}"] as $key => $value) {
            $obj = $GLOBALS["house"]["{$house_id}"][$key];
            if ($obj->id == $connection->id) {
                unset($GLOBALS["house"]["{$house_id}"][$key]);
            }
        }
    }
    catch (Exception $e) {

    }
};

// 运行worker
Worker::runAll();
/*发送消息给指定房间的全局*/
function send_information_all($house_id,$data)
{
    try {
        foreach ($GLOBALS["house"]["{$house_id}"] as $people) {
            $people->send($data);
        }
    }
    catch (Exception $e) {

    }
}
/*发送消息给房间内除了自己以外的人*/
function send_information_removeme($house_id,$data,$meid)
{
    try {
        foreach ($GLOBALS["house"]["{$house_id}"] as $people) {
            if ($people->id != $meid) {
                $people->send($data);
            }
        }
    }
    catch (Exception $e){

    }
}
/*取出redis缓存*/
function getuser_in_reids($token)
{
    global $redis;
    return $redis->hGet('session',$token);
}
/*加载当前房间的成员*/
function getnow_house_people($house_id,$now_user_id)
{
    try {
        global $common_conf;
        $house_array = array();
        foreach ($GLOBALS["house"]["{$house_id}"] as $people) {
            $inforarray = json_decode(getuser_in_reids($people->token), true);
            $inforarray["headportrait"] = $common_conf["Root_URL"] . $inforarray["headportrait"];
            $inforarray["server_id"] = $people->id;
            $row = $inforarray;
            $house_array[] = $row;
        }
        $send_data["type_id"] = 2;
        $send_data["data"] = $house_array;
        $nowobj = $GLOBALS["server_id"]["{$now_user_id}"];
        $nowobj->send(json_encode($send_data));
    }
    catch (Exception $e){

    }
}