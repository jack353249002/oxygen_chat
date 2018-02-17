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
use DataOperation\SqlCommand;
use operate\OperateBase;
use Common\CommonFunction;
class User extends OperateBase
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
    public function insert($data)
    {  //账号注册
        $user=$this->json_to_array($data);
        $sqlcommand= new SqlCommand("user",$this->db);
        $array=$sqlcommand->select("*","nickname='{$user['nickname']}'");
        $dbarray=$array["data"];
        if(count($dbarray)==0) {
            $infor = $sqlcommand->insert($user);
            if ($infor) {
                return array(
                    'type_id' => 1,
                    'msg' => '注册成功!',
                    'data'=> $infor["data"]
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
    public  function  delete()
    {

    }
    public  function land($data)
    {  //用户登录
        $user=$this->json_to_array($data);
        $name=$user["nickname"];
        $passwords=$user["passwords"];
        $sqlcommand= new  SqlCommand("user",$this->db);
        $array=$sqlcommand->select("*","nickname='{$name}' AND passwords='{$passwords}'");
        $dbarray=$array["data"];
        if(count($dbarray)!=0)
        {
            $token=CommonFunction::Create_Token();
            $this->putuser_in_redis($dbarray,$token);
            return array(
                'type_id'=>1,
                'msg'=>'登录成功!',
                'data'=>$token
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
    /*获取用户信息*/
    public  function  get_userinfor($token)
    {
        $sen_token=$this->getuser_in_reids($token);
        if($sen_token==null || $sen_token=="")
        {
            return array(
                'type_id' => 0,
                'msg' => '令牌错误!'
            );
        }
        else
        {
            return array(
                'type_id' => 1,
                'data' => $sen_token
            );
        }
    }
    public function delete_token($token){
        if($this->delete_reids_token($token))
        {
            return array(
                'type_id' => 1,
                'msg' => '成功!'
            );
        }
        else
        {
            return array(
                'type_id' => 0,
                'msg' => '失败!'
            );
        }
    }
    /*将用户信息放入缓存*/
    private function putuser_in_redis($array,$token)
    {
        $this->redis->hSet('session',$token,$this->array_to_json($array));
        return true;
    }
    /*取出redis缓存*/
    private function getuser_in_reids($token)
    {
       return $this->redis->hGet('session',$token);
    }
    /*删除用户redis会话信息*/
    private  function delete_reids_token($token)
    {
        $this->redis->hDel('session',$token);
        return true;
    }
}