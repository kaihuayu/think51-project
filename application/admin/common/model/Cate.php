<?php


namespace app\admin\common\model;


use think\Model;

class Cate extends Model
{
    protected $pk ='id';
    protected $table ='zh_article_category';
    protected  $autoWriteTimestamp = true; //自动时间戳
    protected  $createTime ='create_time'; //创建时间字段
    protected  $updateTime ='update_time'; //更新时间字段
    protected  $dateFormat ='Y年m月d日';

    //开启自动设置
    protected $auto =[]; //无论是新增或更新都要设置的字段
    //仅新增时有效
    protected  $insert =['create_time','status'=>1,'is_top=>0','is_host'=>0];
    //仅更新是设置
    protected  $update =['update_time'];
}