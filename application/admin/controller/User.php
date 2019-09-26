<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;




class User extends Base
{
    //渲染登陆界面
    public function   login(){
        $this->view->assign('title','后台管理登陆');
        return $this->fetch();

    }
  public  function checkLogin(){
        $data = Request::param();
        $map[] = ["email",'=',$data['user']];
        $map[] =['password','=',sha1($data['password'])];
        $res=UserModel::where($map)->find();
        dump($res);
        if($res){
            Session::set('admin_id',$res['id']);
            Session::set('admin_name',$res['name']);
            Session::set('admin_level',$res['is_admin']);//判断是否是高级管理员
            $this->success('登陆成功',url('user/userlist'));

        }
        $this->error('登陆失败');
  }
  public function loginout(){
        Session::clear();
        $this->success('退出成功',url('user/login'));
  }
  public  function userlist(){
        $data['admin_id']=Session::get('admin_id');
        $data['admin_level'] =  Session::get('admin_level');
        $res= UserModel::where('id',$data['admin_id'])->find();
        if ($res['is_admin']==1){
            $list=UserModel::all();
            $this->view->assign('list',$list);
        }else{
            $this->view->assign('list',$res);
        }
      // dump($res);
          $this->view->assign('empty','<span style="color:red">无数据<span>');

       return $this->fetch();
  }
}