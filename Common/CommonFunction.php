<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/13
 * Time: 20:36
 */

namespace Common;


class CommonFunction
{
   static function UploadFile_1($upfile=null,$saveurl="",$returnurl="")
   {
       if($upfile["tmp_name"]!=null && $saveurl!="" && $returnurl!="")
       {
           $tmpfile = $upfile["tmp_name"];
           $filearry =explode(".",$upfile["name"]);
           $filefix=$filearry[1];
           $name=uniqid();
           if (!file_exists($saveurl))
           {
               mkdir ($saveurl,0777,true);
           }
           $dstfile = $saveurl."$name".".".$filefix;
           $returnurl=$returnurl."$name".".".$filefix;
           move_uploaded_file($tmpfile, $dstfile);
           return $returnurl;
       }
   }
   static function Create_Token()  //生成令牌
   {
      return md5(time() . mt_rand(1,1000000));
   }
}