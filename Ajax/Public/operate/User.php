<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 20:38
 */

namespace operate;
include_once("../../DataOperation/DB.php");
include_once("../../DataOperation/SqlCommand.php");
include_once ("OperateBase.php");
use  DataOperation\DB;
use DataOperation\BuildSql;
use DataOperation\SqlCommand;
use operate\OperateBase;
class User extends OperateBase
{
    private $db;
    public function  __construct(){
        $this->db=new DB("localhost","root","","oxygen_chat");
    }
    public function insert($data){  //账号注册
        $user=$this->json_to_array($data);
        $sqlcommand= new SqlCommand("user",$this->db);
        $array=$sqlcommand->select("*","nickname='{$user['nickname']}'");
        $dbarray=$array["data"];
        if(count($dbarray)==0) {
            $infor = $sqlcommand->insert($user);
            if ($infor) {
                return array(
                    'type_id' => 1,
                    'msg' => '注册成功!'
                );
            } else {
                return array(
                    'type_id' => 0,
                    'msg' => '注册失败!'
                );
            }
        }
        else
        {
            return array(
                'type_id' => 2,
                'msg' => '该账号已经注册!'
            );
        }
    }
    public  function  delete(){

    }
    public  function land($data){  //用户登录
        $user=$this->json_to_array($data);
        $name=$user["nickname"];
        $passwords=$user["passwords"];
        $sqlcommand= new  SqlCommand("user",$this->db);
        $array=$sqlcommand->select("*","nickname='{$name}' AND passwords='{$passwords}'");
        $dbarray=$array["data"];
        if(count($dbarray)!=0)
        {
            return array(
                'type_id'=>1,
                'msg'=>'登录成功!',
                'data'=>$dbarray
            );
        }
        else
        {
            return array(
                'type_id'=>0,
                'msg'=>'用户名或密码错误!'
            );
        }
    }
    /*将信息放入缓存*/
    private function put_in_redis($array){
        $redis=new Redis();
        $redis->connect('127.0.0.1',6379);
        $id= $array["id"];
        $nickname=$array[""];
        return true;
    }
}