<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 20:38
 */

namespace operate;
use Common\CommonFunction;
class Upload extends OperateBase
{
   public  function upload_portrait($file)  //上传头像
   {
       global $conf;
       $ymd = date("Ymd");
       $saveurl=$conf["Project_url"]."/Upload/Headportrait/".$ymd.'/';
       $returnurl= "Upload/Headportrait/".$ymd.'/';
       try {
           $url = CommonFunction::UploadFile_1($file, $saveurl, $returnurl);
           return array(
               'type_id' => 1,
               'msg' => '上传成功!',
               'imgurl' => $conf["ROOT"].'/'.$url,
               'dburl'=> $url
           );
       }
       catch (Exception $e){
           return array(
               'type_id' => 0,
               'msg' => '上传失败!'
           );
       }
   }
}