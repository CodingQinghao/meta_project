<?php
// 应用公共文件
use think\facade\Db;
use think\facade\View;

function getRealIP() {
    $forwarded = request()->header("x-forwarded-for");
    if ($forwarded) {
        $ip = explode(',', $forwarded)[0];
    } else {
        $ip = request()->ip();
    }
    return $ip;
}

function checkBlackList($ip) {
    $hasIP = Db::name('blacklist')->where("ip", $ip)->find(); //查找账号
    if ($hasIP != null) {
        exit("您已被禁止访问该应用");
    }
}

function insertBlackList($ip,$remark) {
    Db::name('blacklist')->insert(['ip'=>$ip,'remark'=>$remark]);
}

function checkLogin(){
    if (session('adminId') == null){
        echo '<head><meta http-equiv="refresh" content="10"><meta http-equiv="refresh" content="0;url=/login/loginView"></head>';
        exit;
    }else{
        $userInfo = Db::name('admin')->where("id", session('adminId'))->find();
        View::assign("userInfo", $userInfo);
    }
}