<?php
namespace app\controller;

use app\model\Log as LogModel;
use app\validate\Admin as AdminValidate;
use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Login extends BaseController
{
    public function loginView()
    {
        return View::fetch('/login/login');
    }

    /**
     * doLogin
     * @param account string
     * @param password string
     * @return code json [-1=>F,200=>T]
     * @return msg string
     */

    public function doLogin() {
        Db::name('admin')->where(['id' => 1])->update(['password' => password_hash('2885368', PASSWORD_DEFAULT)]);

        //验证是否为空
        $validate = new AdminValidate();
        if (!$validate->scene('signIn')->check(input())) {
            return json(['code' => -1, 'msg' => $validate->getError()]);
        }
        //检查账户密码
        $hasUser = $this->checkPassword(input('account'), input('password'));
        if ($hasUser['code'] != 200) {
            return json($hasUser);
        }
        //登陆用户
        $this->loginUser($hasUser['data']['id']);
        return json(['code' => 200, 'msg' => "Signin Success"]);
    }
    /**
     * CheckPassword
     * @param account string Account
     * @param password string Password
     * @return code array Status[-1=>no,200=>ok]
     * @return msg array alert
     */
    private function checkPassword($account, $password) {
        //引用Log模块
        $logModel = new LogModel;

        $hasAccount = Db::name('admin')->where("account", $account)->find();

        if ($hasAccount == NULL) {
            $logModel->insertSystemLog('Warning', getRealIP(), 'Login account check', 'Wrong account', 'Account does not exist:' . $account);
            return ['code' => -1, 'msg' => "Wrong Account / Password"];
        }
        if ($hasAccount["password"] != password_verify($password, $hasAccount['password'])) {
            $logModel->insertSystemLog('Warning', getRealIP(), 'Login account check', 'Wrong Password', 'Wrong Password,User ID:' . $hasAccount['id'] . ',account：' . $hasAccount['account']);
            return ['code' => -1, 'msg' => "Wrong Account / Password"];
        }
        if ($hasAccount["status"] == "封禁") {
            $logModel->insertSystemLog('Alert', getRealIP(), 'Login account check', 'Signin baned account', 'Try to Signin baned account,User ID:' . $hasAccount['id'] . ',account：' . $hasAccount['account']);
            return ["code" => -1, "msg" => "baned account"];
        }
        return ["code" => 200, "msg" => "Success", 'data' => $hasAccount];
    }
    /**
     * 登录到某个用户
     * @param id int 账号
     */
    private function loginUser($userId) {
        $logModel = new LogModel;
        $userInfo = Db::name('admin')->where("id", $userId)->find(); //查找账号

        session('adminId', $userInfo['id']);
        session('adminAccount', $userInfo['account']);
        session('userId',1);
        $logModel->insertSystemLog('Note', getRealIP(), 'Login account check', 'Login Successed', 'Login Successed,User ID:' . $userInfo['id'] . ',account：' . $userInfo['account']);
    }
}
