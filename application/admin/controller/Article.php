<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use app\common\model\ArtCate;
use think\Controller;
//use think\Session;
use think\facade\Session;
use think\facade\Request;


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

    public function artEdit(){
        //获取文章id
        $id=Request::get("id");
       // echo $id;
        $id=Request::param('id');
       // echo $id2;
      //$res=ArtModel::where('id',$id);
        $res=ArtModel::get($id);
//获取栏目信息
        $data=Artcate::all();
        //var_dump($res);
        if (count($data) >0) {
            $this->view->assign('catelist', $data);

        }else{
            $this->error('请添加分类',url('/index/index'));
        }

        $this->view->assign('res',$res);
        return $this->view->fetch();

    }
    public function save(){
        //判断提交类型
        if (Request::isPost()){
            //获取用户发不的信息
            $data =Request::post();
            // halt($data); //调试方法测试是否获取数据
            $res=$this->validate($data,'app\common\validate\Article');
            if(true!==$res){
                //验证失败
                echo '<script>alert("'.$res.'");history.go(-1);</script>';

            }else{
                //验证成功写入数据库
                //获取图片信息
                $file=Request::file('title_img');
                //validate 方法检查文件是否合格，move方法上传到uploads 目录$info保存上传的目录信息
                $info=$file->validate(['ext'=>'jpg,jpeg,png,gif','size'=>150000])->move('uploads');
                //判断是否上传成功
                if($info){
                    $data['title_img'] = $info->getSaveName(); //写人文件保存信息
                }else{
                    $this->error($file->getError());
                }
                //数据写入数据库
                //$Article = new Article();
                $res=ArtModel::update($data);
                if($res){
                    $this->success('文章更新成功',url('/admin/article'));
                }else{
                    $this->error('发布失败',url('/admin/article'));
                }
            }

        }else{

            $this->error('请求类型错误');
        };
    }

    public function del(){

    }


}