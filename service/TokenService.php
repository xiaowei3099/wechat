<?php

namespace app\service;

use yii;
class TokenService
{
    //获取access_token并保存到token.txt文件中
    public static function build_access_token(){
        $appid = Yii::$app->params['wechat']['appid'];
        $appsecret = Yii::$app->params['wechat']['appsecret'];
        $redis = Yii::$app->redis;
        $ch = curl_init(); //初始化一个CURL对象
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);//设置你所需要抓取的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置curl参数，要求结果是否输出到屏幕上，为true的时候是不返回到网页中,假设上面的0换成1的话，那么接下来的$data就需要echo一下。
        $data = json_decode(curl_exec($ch));
        if($data->access_token){
            $redis->set('token',$data->access_token);
        }else{
            echo $data->errmsg;
        }
        curl_close($ch);
    }

    //设置定时器，每两小时执行一次build_access_token()函数获取一次access_token
    public static function set_interval(){
        ignore_user_abort();//关闭浏览器仍然执行
        set_time_limit(0);//让程序一直执行下去
        $interval = 7200;//每隔一定时间运行
        do{
            self::build_access_token();
            sleep($interval);//等待时间，进行下一次操作。
        }while(true);
    }

    //读取token
    public static function read_token(){
        $token_file = fopen("./token.txt", "r") or die("Unable to open file!");
        $rs = fgets($token_file);
        fclose($token_file);
        return $rs;
    }
}