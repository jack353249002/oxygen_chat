<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8
 * Time: 9:19
 */

namespace DataOperation;


class BuildSql
{
    protected $table_name="";
    protected $insert="INSERT INTO %table% (%column%) VALUES (%value%)";
    protected $update="UPDATE %table% SET %assignment% WHERE %conditions%";
    protected $delete="DELETE FROM %table% WHERE %conditions%";
    protected $select='SELECT %column% FROM %table% WHERE %conditions%';
    public  function  __construct($table_name)
    {
        $this->table_name=$table_name;
    }
    public function  select($column,$conditions) //生成查询语句
    {
        $sql=str_replace("%table%",$this->table_name,$this->select);
        $sql=str_replace("%conditions%",$conditions,$sql);
        $sql=str_replace("%column%",$column,$sql);
        return $sql;
    }
    public  function  insert($column,$values) //生成添加语句
    {
        $sql=str_replace("%table%",$this->table_name,$this->insert);
        $sql=str_replace("%column%",$column,$sql);
        $sql=str_replace("%value%",$values,$sql);
        return $sql;
    }
    public  function update($assignment,$conditions) //生成更新语句
    {
        $sql=str_replace("%table%",$this->table_name,$this->update);
        $sql=str_replace("%assignment%",$assignment,$sql);
        $sql=str_replace("%conditions%",$conditions,$sql);
        return $sql;
    }
    public  function delete($conditions)  //生成删除语句
    {
        $sql=str_replace("%conditions%",$conditions,$this->delete);
        $sql=str_replace("%table%",$this->table_name,$sql);
        return $sql;
    }
    public  function  array_to_sql_filter($array)
    {
        $sql="";
        foreach ($array as &$value)
        {
            $sql=$sql.$value.',';
        }
        return   substr($sql,0,strlen($sql)-1);
    }
    public  function array_to_sql_value($array)
    {
        $sql="";
        foreach ($array as &$value)
        {
            $sql=$sql.$this->filter($value).',';
        }
        return   substr($sql,0,strlen($sql)-1);
    }
    public function array_to_update($array)
    {
        $sql="";
        foreach ($array as $key=>$value)
        {
            $sql=$sql.$key.'='.$this->filter($value).',';
        }
        return   substr($sql,0,strlen($sql)-1);
    }
    private function filter($value)  //将字符类型变量添加''
    {
        switch (gettype($value))
        {
            case "string":
                return "'".$value."'";
            break;
            case "NULL":
                return "null";
            break;
            default:
                return $value;
        }
    }
}