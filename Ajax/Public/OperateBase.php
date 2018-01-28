<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12
 * Time: 9:09
 */

namespace operate;


class OperateBase
{
    protected function json_to_array($json)
    {
        return json_decode($json,true);
    }
}