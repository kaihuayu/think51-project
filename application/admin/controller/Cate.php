<?php


namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
    public  function cateList(){
        //判断是否登陆
        $this->islogin();
        //l列出所有分类
        $res=CateModel::all();
        //设置模板变量
        $this->view->assign('title',"分类管理");
        $this->assign("empty","<span>没有分类内容</span>");
        $this->view->assign('catlist',$res);
        //渲染视图
       return $this->fetch();
    }

    public function cateEdit(){
        //获取id
        $id=Request::param("id");
        //根据ID 查询
        $data=CateModel::get($id);
        //设置模板变量
        $this->view->assign('res',$data);
        return $this->fetch();
    }

    public function cateSave()
    {
        //获取表单数据
        $data = Request::param();
        //保存ID
        $id = $data['id'];
        //删除ID
        unset($data["id"]);
        //$res=CateModel::where('id',2)->update($data);
        // dump($id);
        $res = CateModel::where('id', $id)->update($data);
        if ($res) {

            return $this->success('保存成功', 'catelist');
        } else {
            return $this->error('保存失败', 'catelist');
        }
    }
       public function cateAdd()
       {

           $data = Request::param();
           if ($data) {
               $res = CateModel::create($data);
               if ($res) {
                   return $this->success('添加成功', 'catelist');
               } else {
                   return $this->error('添加失败', 'catelist');
               }

           }
           return $this->fetch();
       }

       public function cateDel(){
        $id= Request::param('id');
        if (CateModel::where('id',$id)->delete()){
           return $this->success('删除成功','catelist');
        }else{
           return $this->error('删除失败','catelist');
        }
       }

}