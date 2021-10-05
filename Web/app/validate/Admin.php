<?php

namespace app\validate;

use think\Validate;

class Admin extends Validate {

    protected $rule = [
        'account' => 'require',
        'password' => 'require',
    ];
    protected $message = [
        'account.require' => 'please input account',
        'password.require' => 'please input password',
    ];
    protected $scene = [
        'signIn' => ['account', 'password']
    ];

}
