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
 * Description of Log
 *
 * @author QingHao
 */
class Log extends Model {

    protected $name = 'sys_log';


    /**
     * 系统日志列表
     * @param string level  等级['正常','预警','严重']
     * @param int $ip IP
     * @param string $type 模块类别
     * @param string $title 标题
     * @param string $content 内容
     * @param string $status 状态['未读','已读']
     */
    function insertSystemLog($level, $ip, $type, $title, $content, $status = '未读') {
        Db::name('sys_log')->insert(['ip' => $ip, 'level' => $level, 'type' => $type, 'title' => $title, 'content' => $content, 'status' => $status, 'create_time' => date('Y-m-d H:i:s', time())]);
    }
    
    /**
     * 更新即读
     * @param array $map 条件数组
     */
    function updateIsRead($map) {
        Db::name('sys_log')->where($map)->update(['status'=>'已读']);   
    }

    /**
     * 获取日志列表
     * @param array $map 条件数组
     * @param int $nowPage 当前页面
     * @param int $limits 限制条数
     * @param array $orderBy 排序规则
     * @return $data 数据
     */
    function getSystemLogList($map, $nowPage, $limits, $orderBy = "id desc") {
        $data = array();
        $data['data'] = Db::name('sys_log')->field("*")->alias('a')->where($map)->page($nowPage, $limits)->order($orderBy)->select();
        $data['rowIds'][] = array();
        foreach ($data['data'] as $arr => $row) {
            $data['rowIds'][] = $row["id"];
        }
        $data['count'] = Db::name('sys_log')->alias('a')->where($map)->count();
        return $data;
    }

}
