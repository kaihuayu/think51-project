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
use app\common\model\Article;
use app\common\model\Site;
use think\facade\Request;
class Base extends Controller
{
    /*
 * 初始化方法
 * 创建常量，公共方法
 * 在所有方法之前被调用

*/
    protected function initialize(){
        $this->shownav();
        $this->IsOpen();
        $this->HotArtList();
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
        //显示分类导航
        protected function shownav(){
                //获取分类导航
               $res= ArtCate::all(function($query){
                   $query->where('status',1)->order('sort','asc');
               });
               //将分类信息赋值给模板
            $this->view->assign('cateList',$res);

            }
   //检测站点是否关闭
     public function IsOpen(){
        //获取站点是否关闭
       $isopen= Site::where('status',1)->value("is_open");

       //如果已经关闭，就关闭前台，后台开启 Request::module() 检测前台模块
         if($isopen==0 && Request::module()=="index"){

             $info="<body STYLE=background-color:#2d2d2a;text-align:center;><h1 style='color: #fff;font-weight: bold;margin-top:10%'>网站维护中</h1></body>";
             exit($info);
         }

     }

     //检测是否允许注册
    public function IsReg(){
        //获取注册状态
        $isreg = Site::where('status',1)->value("is_reg");
        //根据状态 跳转
        if($isreg==0){

            $this->error("关闭注册中",url('/index/index'));

        }

    }

    //热门文章列表
    public function HotArtList(){
        $hotartlist = Article::where('status',1)->order('pv','desc')->limit(12)->select();
        $this->view->assign('hotartlist',$hotartlist);
    }



}