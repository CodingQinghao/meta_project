<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Translate extends BaseController
{
    public function addTask()
    {
        $sentence = input('sentence_list');
        $html = input('html');
        $source = input('source');
        $target = input('target');
        $type = input('type');
        $goodsId = input('goods_id');
        $email = input('email');
        $seccode = md5($this->getRandomStr(32));
        if ($email == null){
            return json(['code'=>-1,'msg'=>'email cannot null']);
        }

        if ($source == $target){
            return json(['code'=>-1,'msg'=>'The source language cannot be the same as the target language']);
        }
        $taskId = Db::name('translate_task')->insertGetId(['user_id' => session("user_id"),
            'source'=>$source,'target'=>$target,
            'type' => $type, 'html' => $html,
            'create_time' => date('Y-m-d H:i:s', time()),
            'email'=>$email,
            'goods_id'=>$goodsId,
            'seccode'=>$seccode
        ]);

        foreach ($sentence as $arr => $row) {
            Db::name('translate_sentence')->insert(['task_id' => $taskId,'sentence'=>$row]);
        }
        return json(['code'=>200,'msg'=>'The task has begun and will be completed in two minutes.']);
    }


    /**
     * randomStr
     * @param $len
     * @param $special
     * @return string
     */
    function getRandomStr($len, $special=true){
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );

        if($special){
            $chars = array_merge($chars, array(
                "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
                "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
                "}", "<", ">", "~", "+", "=", ",", "."
            ));
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);                            //打乱数组顺序
        $str = '';
        for($i=0; $i<$len; $i++){
            $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }
        return $str;
    }
}