<?php
/*
 * 基础控制器
 * 基础控制器公共 继承自 think\controller
 * 其他控制就直接继承 Base  就可以了 不用继承Controller
 */

namespace app\common\controller;
use think\Controller;
use think\db\Query;
use think\facade\Session;
use app\common\model\ArtCate;

class Base extends Controller
{
    /*
 * 初始化方法
 * 创建常量，公共方法
 * 在所有方法之前被调用

*/
    protected function initialize(){
        $this->shownav();
    }
    // 重复登陆检测
    protected function logined()
    {
        if (Session::has('user_id')) {
            $this->error('您已经登陆了');
        }
    }
    //检查是否登陆
        protected function islogin()
        {
            if (!Session::has('user_id')) {
                $this->error('请登录');
            }
        }
        protected function shownav(){
                //获取分类导航
               $res= ArtCate::all(function($query){
                   $query->where('status',1)->order('sort','asc');
               });
               //将分类信息赋值给模板
            $this->view->assign('cateList',$res);

            }



}