<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/9
 * Time: 19:33
 */

namespace DataOperation;

use DataOperation\BuildSql;
class SqlCommand
{
    protected $table;
    protected $DB;
    public function __construct($table,$db)
    {
        $this->table=$table;
        $this->DB=$db;
    }
    public function query($sqlstr)  //执行sql语句
    {
        $obj= mysqli_query($this->get_linkobj(),$sqlstr);
        if(!$obj)
        {
            return array(
                'type_id'=>0,
                'msg'=>"执行失败!",
                'data'=>null
            );
        }
        else
        {
            return array(
                'type_id'=>1,
                'msg'=>"执行成功!",
                'data'=>$obj->fetch_array()
            );
        }
    }
    public function  select($column,$conditions)
    {
          $buildsql= new BuildSql($this->table);
          $sqlstr=$buildsql->select($column,$conditions);
          $obj=mysqli_query($this->get_linkobj(),$sqlstr);
           if(!$obj)
           {
               return array(
                   'type_id'=>0,
                   'msg'=>mysqli_error($this->get_linkobj())
               );
           }
           else
           {
               return array(
                   'type_id'=>0,
                   'msg'=>"查询成功!",
                   'data'=>$obj->fetch_array()
               );
           }
    }
    public  function insert($data)
    {
        $buildsql = new BuildSql($this->table);
        $field = array();//字段数组
        $val = array();//值数组
        foreach ($data as $key => $value) {
            $field[] = $key;
            $val[] = $value;
        }
        $columnstr = $buildsql->array_to_sql_filter($field);
        $valuestr = $buildsql->array_to_sql_value($val);
        $sqlstr=$buildsql->insert($columnstr, $valuestr);
        if(!mysqli_query($this->get_linkobj(),$sqlstr))
        {
           return array(
               'type_id'=>0,
               'msg'=>mysqli_error($this->get_linkobj())
            );
        }
        else
        {
            return array(
                'type_id'=>1,
                'msg'=>"添加成功!"
            );
        }
    }
    public  function update($data,$conditions)
    {
        $buildsql= new BuildSql($this->table);
        $value = $buildsql->array_to_update($data);
        $sqlstr=$buildsql->update($value,$conditions);
        if(!mysqli_query($this->get_linkobj(),$sqlstr))
        {
            return array(
                'type_id'=>0,
                'msg'=>mysqli_error($this->get_linkobj())
            );
        }
        else
        {
            return array(
                'type_id'=>1,
                'msg'=>"修改成功!"
            );
        }
    }
    public  function  delete($conditions)
    {
        $buildsql= new BuildSql($this->table);
        $sqlstr=$buildsql->delete($conditions);
        if(!mysqli_query($this->get_linkobj(),$sqlstr))
        {
            return array(
                'type_id'=>0,
                'msg'=>mysqli_error($this->get_linkobj())
            );
        }
        else
        {
            return array(
                'type_id'=>1,
                'msg'=>"删除成功!"
            );
        }
    }
    private function get_linkobj() //获取连接对象
    {
        return $this->DB->linkobj;
    }

}