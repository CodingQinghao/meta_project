<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

use app\model\Order as OrderModel;

class Customer extends BaseController
{
    public function customerPageView()
    {
        $seccode = input("seccode");
        $orderModel = new OrderModel();
        $info = $orderModel->getInfoByseccode($seccode);
        View::assign("info", $info);
        return View::fetch('/customer/result');
    }

    public function getSentence()
    {
        $sentence = input('sentence');
        $seccode = input('seccode');
        $orderModel = new OrderModel();
        $info = $orderModel->getInfoByseccode($seccode);
        if ($info==null){
            return json(['code' => -1, 'msg' => 'No permission']);
        }

        $result = Db::name('translate_sentence')->where(['sentence' => $sentence,'task_id'=>$info['id']])->find();
        return json(['code' => 200, 'msg' => 'success', 'data' => $result['translated_sentence']]);
    }

    public function getTableWord()
    {
        $word = input('word');
        $result = Db::name('translate_table_word')->where(['source_word' => $word])->find();
        return json(['code' => 200, 'msg' => 'success', 'data' => $result['target_word']]);
    }
}