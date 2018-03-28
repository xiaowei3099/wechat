<?php
/**
 * Created by PhpStorm.
 * User: zhangfawei
 * Date: 2018/3/27
 * Time: 9:46
 */

namespace app\service;


class ToolService
{
public static function CurlGet($request_url){
    //初始化一个curl会话
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$request_url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
}