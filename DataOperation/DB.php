<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 21:16
 */

namespace DataOperation;
include_once("BuildSql.php");
use DataOperation\BuildSql;

/*DB是连接mysql数据库的类*/
class DB
{
    private $address; //数据库地址
    private $user_name;//数据库用户名
    private $passwords;//数据库密码
    private $db_name;//数据库名
    public $linkobj=null;//连接对象
    public function __construct($address="",$user_name="",$passwords="",$db_name="")
    {
        $this->address = $address;
        $this->user_name = $user_name;
        $this->passwords = $passwords;
        $this->db_name = $db_name;
        $this->linkobj = mysqli_connect($this->address, $this->user_name, $this->passwords, $this->db_name);
    }
    public function close() //关闭数据库
    {
        mysqli_close($this->linkobj);
    }
    public  function  insert($data,$table_name)
    {
        $buildsql= new BuildSql($table_name);
        $field=array();
        $val=array();
        foreach ($data as $key=>$value)
        {
            $field[]=$key;
            $val[]=$value;
        }
        $columnstr=$buildsql->array_to_sql($field);
        $valuestr=$buildsql->array_to_sql($val);
        print_r($buildsql->insert($columnstr,$valuestr));
        $obj= mysqli_query($this->linkobj,$buildsql->insert($columnstr,$valuestr));
    }
}