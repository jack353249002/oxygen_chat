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
$http_worker->count = 1;
$http_worker->onConnect=function($connection) use($http_worker)
{
   $GLOBALS["server_id"]["{$connection->id}"]=$connection;
};
$http_worker->onMessage = function($connection, $data)use($http_worker,$common_conf)
{
    $infor=json_decode($data,true);
    $house_id=$infor["house_id"];
    $token=$infor["token"];
    switch ($infor["type_id"])
    {
        /*进入房间加入数组*/
        case 1:
            $connection->token=$token;
            $connection->house_id=$house_id;
            $GLOBALS["house"]["{$house_id}"][]=$connection;
            $userinfor=json_decode(getuser_in_reids($token),true); //获取用户信息
            $userinfor[0]["headportrait"]=$common_conf["Root_URL"].$userinfor[0]["headportrait"];
            $userinfor[0]["server_id"]=$connection->id;
            $jsondata["type_id"]=1;
            $jsondata["data"]=json_encode($userinfor);
            send_information_removeme($house_id,json_encode($jsondata),$connection->id);
            getnow_house_people($house_id,$connection->id);
            break;
        /*发送消息*/
        case 2:
            $sendinfor["type_id"]=3;
            $chat_infor["chat_infor"]=$infor["chat_infor"];
            $chat_infor["user_infor"]=json_decode(getuser_in_reids($token),true); //获取用户信息
            $chat_infor["user_infor"][0]["headportrait"]=$common_conf["Root_URL"].$chat_infor["user_infor"][0]["headportrait"];
            $sendinfor["data"]=json_encode($chat_infor);
            send_information_all($house_id,json_encode($sendinfor));
            break;
    }

};
$http_worker->onClose = function($connection) use($http_worker)
{
    $data["type_id"]=0;
    $data["data"]=$connection->id;
    $house_id=$connection->house_id;
    send_information_all($house_id,json_encode($data));
    /*删除信息*/
   foreach($GLOBALS["house"]["{$house_id}"] as $key=>$value)
   {
       $obj=$GLOBALS["house"]["{$house_id}"][$key];
       if($obj->id==$connection->id) {
           unset($GLOBALS["house"]["{$house_id}"][$key]);
       }
   }
};

// 运行worker
Worker::runAll();
/*发送消息给指定房间的全局*/
function send_information_all($house_id,$data)
{
    foreach($GLOBALS["house"]["{$house_id}"] as $people)
    {
        $people->send($data);
    }
}
/*发送消息给房间内除了自己以外的人*/
function send_information_removeme($house_id,$data,$meid)
{
    foreach($GLOBALS["house"]["{$house_id}"] as $people)
    {
        if($people->id != $meid)
        {
            $people->send($data);
        }
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
    global $common_conf;
    $house_array=array();
    foreach($GLOBALS["house"]["{$house_id}"] as $people)
    {
        $inforarray=json_decode(getuser_in_reids($people->token),true);
        $inforarray[0]["headportrait"]= $common_conf["Root_URL"].$inforarray[0]["headportrait"];
        $inforarray[0]["server_id"]=$people->id;
        $row["infor"]=json_encode($inforarray);
        $house_array[]=$row;
    }
    $send_data["type_id"]=2;
    $send_data["data"]=json_encode($house_array);
    $nowobj=$GLOBALS["server_id"]["{$now_user_id}"];
    $nowobj->send(json_encode($send_data));
}