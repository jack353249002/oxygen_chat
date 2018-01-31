<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 22:53
 */
$con=mysqli_connect("localhost","root","","oxygen_chat");
mysqli_query($con,'set names utf8');
mysqli_query($con,"INSERT INTO `house`(`id`, `name`, `userid`, `havepass`, `passwords`) VALUES(NULL,'王俊松','50',1,'123')");