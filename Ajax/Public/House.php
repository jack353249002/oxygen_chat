<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 20:37
 */
include_once ("operate/House.php");
include_once ("../../Common/CommonFunction.php");
$db_conf=include_once("../Conf/DB.php");
$redis_conf=include_once("../Conf/Redis.php");
use operate\House;
$Class=$_GET["Class"];
$Function=$_GET["Function"];
$Data=$_GET["Data"];
$ref_class = new ReflectionClass("operate\\".$Class); //反射类
$instance  = $ref_class->newInstance();// 实例化类
$method = $ref_class->getmethod($Function); //获取指定方法
$method->setAccessible(true); //设置访问权限
$json=$method->invoke($instance,$Data); //执行方法(传递参数)
echo json_encode($json);