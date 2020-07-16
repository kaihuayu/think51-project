<?php


namespace app\admin\controller;
use \app\common\model\Site as SiteModel;

use app\admin\common\controller\Base;
use think\Controller;

class Site extends Base
{
    public function index(){
        //获取站点信息
        $setinfo = SiteModel::get(["is_open"=>1]);
        //为模板赋值
        $this->view->assign("info",$setinfo);
        //渲染模板
        return $this->view->fetch();
    }


}