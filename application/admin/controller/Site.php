<?php


namespace app\admin\controller;
use \app\common\model\Site as SiteModel;

use app\admin\common\controller\Base;
use think\Controller;
use think\facade\Request;

class Site extends Base
{
    public function index(){
        //获取站点信息
        $setinfo = SiteModel::get(["status"=>1]);
        //为模板赋值
        $this->view->assign("info",$setinfo);
        //渲染模板
        return $this->view->fetch();
    }

     public function sitedit(){
        $data= Request::param();

        $res = SiteModel::update($data);

        if($res){
            $this->success("更新成功");

        }
        $this->error("更新失败");

     }
}