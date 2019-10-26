<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;
use think\Db;




class User extends Base
{
    //渲染登陆界面
    public function   login(){
        $this->view->assign('title','后台管理登陆');
        return $this->fetch();

    }
  public  function checkLogin(){
        $data = Request::param();
        $map[] = ['name','=',$data['user']];
        $map[] =['password','=',sha1($data['password'])];
        $res=UserModel::where($map)->find();
        //dump($map);
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
        //获取用的的id 和 级别
        $data['admin_id']=Session::get('admin_id');
        $data['admin_level'] =  Session::get('admin_level');
        $res= UserModel::where('id',$data['admin_id'])->find();
        //如果是超级管理员获取全部数据
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

  public function userEdit(){
        //1.获取用户ID
      $id=Request::param('id');
       //2.根据ID 查询用户
      $res=UserModel::get($id);
      //3.设置模板变量
      $this->view->assign('res',$res);
    //  dump($res); 渲染模板
      return $this->fetch();
  }

  public function usersave(){
        //1.获取所有数据
        $data =Request::param();
        //保存主键id
        $id =$data['id'];
        //删除id
         unset($data['id']);
         unset($data['password-confirm']);

        //检查密码是否为空
      if (empty($data['password'])){
          //密码为空不修改删除
          unset($data['password']);

        $rs= UserModel::where('id',$id)->update($data);
        if($rs){
           return $this->success('更新成功','userlist');
        }
      }else{
          $data['password']=sha1($data['password']);
          $rs= UserModel::where('id',$id)->update($data);
        return  $this->success('更新成功','userlist');
      }
        return $this->error('修改失败');
  }

  public function doDelete(){
        $id= Request::param('id');
      if(  UserModel::where('id',$id)->delete()){
          return $this->success('删除成功','userlist');
      };
      return $this->error('删除失败','userlist');
  }

}