<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\model;

use think\Model;
use think\facade\Db;

/**
 * 商铺模型
 *
 * @author QingHao
 */
class Order extends Model {
    protected $name = 'translate_task';
    /**
     * GetOrderList
     * @param array $map
     * @param int $nowPage
     * @param int $limits
     * @param array $orderBy
     * @return $data
     */
    function getList($map, $nowPage, $limits, $orderBy = "id desc") {
        $data = array();
        $data['data'] = Db::name('translate_task')->field("a.*,b.name")->alias('a')->leftJoin('goods b','a.goods_id = b.id')->where($map)->page($nowPage, $limits)->order($orderBy)->select()->all();
        $data['rowIds'][] = array();
        foreach ($data['data'] as $arr => $row) {
            $data['rowIds'][] = $row["id"];

        }
        $data['count'] = Db::name('translate_task')->alias('a')->where($map)->count();
        return $data;
    }

    /**
     * GetOrderInfo
     * @param array $id 用户数组
     */
    function getInfo($id) {
        $info = Db::name('translate_task')->field("a.*,b.name")->alias('a')->leftJoin('goods b','a.goods_id = b.id')->where("a.id",$id)->find();
        return $info;
    }
    /**
     * GetOrderInfoBySeccode
     * @param array $id 用户数组
     */
    function getInfoByseccode($seccode) {
        $info = Db::name('translate_task')->field("a.*,b.name")->alias('a')->leftJoin('goods b','a.goods_id = b.id')->where("a.seccode",$seccode)->find();
        return $info;
    }

}
