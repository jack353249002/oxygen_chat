<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/13
 * Time: 20:32
 */
header("Access-Control-Allow-Origin: *");
require_once ("OperateBase.php");
require_once ("../../Common/CommonFunction.php");
require_once ("operate/Upload.php");
$conf= include_once ("../Conf/Url.php");
use operate\Upload;
use Common\CommonFunction;
$Class=$_POST["Class"];
$Function=$_POST["Function"];
$file = $_FILES["file"];
$ref_class = new ReflectionClass("operate\\".$Class); //反射类
$instance  = $ref_class->newInstance();// 实例化类
$method = $ref_class->getmethod($Function); //获取指定方法
/*$method->setAccessible(true); //设置访问权限*/
try {
    $json = $method->invoke($instance, $file); //执行方法(传递参数)
    echo json_encode($json);
}
catch (Exception $e){
    $json=array(
        'type_id'=>'0',
        'msg'=>'请求错误!'
    );
    echo json_encode($json);
}