<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;

class Index extends Base
{
    public function index(){
        $this->islogin();
         $this->assign('title','管理后台');
        return $this->view->fetch();

    }
}