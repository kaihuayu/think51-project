<?php


namespace app\index\controller;


use app\common\controller\Base;
//use think\Request;
use think\facade\Request;
use think\facade\Session;
use app\common\model\User as UserModel ;

class User extends Base
{
    public function register(){
        $this->assign('title',"注册");

      return $this->fetch();
    }

    //注册
    public function insert(){
        if (Request::isAjax()){ //是否是ajax提交
            //获取提交数据
            //验证数据
            $res =Request::post();
            $v=$this->validate($res,'app\common\validate\User');
            if(true!==$v){
                return  ['status'=>-1,'message'=>$v]; //验证失败
            }else{
                $data=Request::except('password-confirm','post');
                //UserModel::create()返回的是一个对象
                if($re=UserModel::create($data)){
                   $inf=UserModel::get($re->id);   //注册成功获取注册id通过ID 获取姓名信息
                    //halt($re);
                    Session::set('user_id',$inf->id);
                    Session::set('user_name',$inf->name);  //保存在session 实现注册成功既登陆
                    return ['status'=>1,'message'=>'注册成功'];
                }else{
                    return ['status'=>0, 'message'=>'注册失败'];
                }
            }

        }else{
            $this->error('请求错误','register');
        }
    }

    public function login(){
        $this->logined();
        return $this->fetch('login',['title'=>'登陆']);
    }
    public function logincheck(){
        if(Request::isAjax()){
            $data =Request::post();
            $rule=[
                'email|邮箱'=>'require|email',
                'password|密码'=>'require|alphaNum',
            ];
            $res = $this->validate($data,$rule);
            if (true !== $res){
                return ['status'=>-1,'message'=>$res];
            }else{
                //执行查询    使用闭包函数 用use 引用外部变量￥data
                $result = UserModel::get(function ($query) use ($data){
                    $query->where('email',$data['email'])
                               ->where('password',sha1($data['password']));
                });

                if (null == $result){
                     return ['status'=>0,'message'=>'登陆失败,请检查邮箱密码'];
                }else{
                     Session::set('user_id',$result->id);
                     Session::set('user_name',$result->name);
                    return ['status'=>1,'message'=>'登陆成功'];
                }

            }

        }else{
            $this->error('请求错误','register');
        }
    }

    public  function loginout(){
       // Session::delete('user_id');  删除 session
       // Session::delete('user_name');
        Session::clear();
        return $this->success('退出成功',url('/index/index'));
    }

}