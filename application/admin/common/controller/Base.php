<?php
//后台公共控制器

namespace app\admin\common\controller;


use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    protected function  initialize(){
        //初始化方法
    }

    protected  function  islogin(){
        //检查登陆
        if (!Session::has('admin_id')) {
            $this->error('请登录后发布');
        }
    }
}
