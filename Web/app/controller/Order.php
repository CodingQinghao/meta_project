<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

use app\model\Order as OrderModel;

class Order extends BaseController
{
    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        checkLogin();
    }
    /**
     * Orderlist
     * @access public
     */
    public function getList() {
        $nowPage = input('pagination.page') ? intval(input('pagination.page')) : 1;
        $limits = input('pagination.perpage') ? intval(input('pagination.perpage')) : 100;
        $orderBy = input('sort.field') . ' ' . input('sort.sort');
        $orderBy = " " ? null : $orderBy;
        $search = input('query.key');
        $status = input('query.status');

        $map = [];
        if ($search && $search !== "") {
            $map[] = ['id|name|email|task', 'like', '%' . $search . "%"];
        }
        if ($status && $status !== "") {
            $map[] = ['status', '=', $status];
        }



        $orderModel = new OrderModel();
        $list = $orderModel->getList($map, $nowPage, $limits, $orderBy);
        $mate['page'] = $nowPage;
        $mate['pages'] = ceil($list['count'] / $limits);
        $mate['perpage'] = $limits;
        $mate['total'] = $list['count'];
        $mate['rowIds'] = $list["rowIds"];
        $mate['sort'] = 'desc';
        $mate['field'] = 'id';
        return json(['code' => 200, 'msg' => '获取成功', 'data' => $list["data"], 'meta' => $mate]);
    }

    public function translateLogView()
    {
        return View::fetch('/order/translate_log');
    }

}