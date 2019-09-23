<?php
/*
 * 测试专业类
 * */

namespace app\index\controller;
//use app\common\controller\Base;
use app\common\controller\Base;
//use think\Controller;
use app\common\model\User;

class Test  extends Base
{
       public function test(){
           $data =[
               'name' =>'patterere',
               'email' => 'adfa@163.com',
               'mobile' =>'18810577458',
               'password'=>'124571asfas'

           ];
           $rule ='app\common\validate\User';
           return $this->validate($data,$rule);

       }

       public function test1(){
          dump(  user::get(10));
       }
}