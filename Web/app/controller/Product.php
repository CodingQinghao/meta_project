<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;
use app\model\Order as OrderModel;
class Product extends BaseController
{
    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        checkLogin();
    }

    public function listView()
    {
        return View::fetch('/product/list');
    }

    public function product_1()
    {
        return View::fetch('/product/product_1');
    }

    public function product_1_result()
    {
        $id = input("id");
        $orderModel = new OrderModel();
        $info = $orderModel->getInfo($id);
        View::assign("info", $info);
        return View::fetch('/product/product_1_result');
    }
}