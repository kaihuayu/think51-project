<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use think\Controller;
//use think\Session;
use think\facade\Session;

class Article extends Base
{
    public function index(){
        //判断是否登陆
        $this->islogin();
        //返回视图
        return $this->redirect('artList');
    }

    public function  artList(){
        //或许当前用户的ID
        $userId = Session::get('admin_id');
        $isAdmin = Session::get('admin_level');

        //获取当前用户的文章
        $artList = ArtModel::where('user_id',$userId)->paginate(5);

        // 判断当前是否是管理员
        if($isAdmin==1){
            $artList = ArtModel::paginate(5);
        }
        //dump($userId);
        //设置模板变量
        $this->view->assign('title',"文章管理");
        $this->assign("empty","<span>没有文章</span>");
        $this->view->assign('artList',$artList);
        return $this->view->fetch();
    }
}