<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 20:39
 */

namespace operate;
require_once("../../DataOperation/DB.php");
require_once("../../DataOperation/SqlCommand.php");
require_once ("OperateBase.php");
use  DataOperation\DB;
use DataOperation\SqlCommand;
use operate\OperateBase;
use Common\CommonFunction;
class House extends OperateBase
{
    private $db;
    private $redis;
    public function  __construct()
    {
        global $db_conf;
        global $redis_conf;
        $this->db=new DB($db_conf["server"],$db_conf["user_name"],$db_conf["passwords"],$db_conf["db_name"]);
        $this->redis=new \Redis();
        $this->redis->connect($redis_conf["server"],$redis_conf["port"]);
    }
    /*获取所有房间*/
    public function get_house($data)
    {
        $sqlcommand= new  SqlCommand("house",$this->db);
        $array=$sqlcommand->select("*","1");
        $dbarray=$array["data"];
        return array(
            'type_id'=>1,
            'data'=>$dbarray
        );
    }
    /*创建房间*/
    public function create_house($data)
    {
        $house=$this->json_to_array($data);
        $house_insert["name"]=$house["name"];
        $house_insert["userid"]=$this->getuser_in_reids_id($house["token"]);
        $house_insert["passwords"]=$house["passwords"];
        if($house_insert["passwords"]!="")
        {
            $house_insert["havepass"]=1;
            $house_insert["passwords"]=$house["passwords"];
        }
        else
        {
            $house_insert["havepass"]=0;
            $house_insert["passwords"]=null;
        }
        $sqlcommand= new SqlCommand("house",$this->db);
        $infor = $sqlcommand->insert($house_insert);
        if ($infor) {
            return array(
                'type_id' => 1,
                'msg' => '创建成功!',
                'data'=> $infor["data"]
            );
        } else {
            return array(
                'type_id' => 0,
                'msg' => '创建失败!'
            );
        }
    }
    /*获取房间的历史记录*/
    public function get_infor_old($data)
    {
        $infor_array=$this->json_to_array($data);
        $house_id=$infor_array["house_id"];
        $sqlcommand= new  SqlCommand("house",$this->db);
        $house_array=$sqlcommand->query("SELECT *, a.id AS content_key FROM content AS a JOIN user AS b ON a.houseid={$house_id} AND a.userid=b.id");
        $conf=include_once("../Conf/Url.php");
        foreach($house_array["data"] as &$value)
        {
            $value["headportrait"] = $conf["ROOT"] . '/' . $value["headportrait"];
        }
        return array(
            'type_id'=>1,
            'data'=>$house_array["data"]
        );
    }
    /*取出redis缓存*/
    private function getuser_in_reids_id($token)
    {
        $json= $this->redis->hGet('session',$token);
        $array=$this->json_to_array($json);
        return $array["id"];
    }
}