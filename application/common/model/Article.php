<?php


namespace app\common\model;
use think\Model;

class Article  extends  Model
{
    protected $pk = "id"; //表的主键默认
    protected  $table  ="zh_article";//模型绑定的表
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

    public function hasoneuser(){
       // return $this->belongsTo('User','id','user_id');
        return $this->hasOne('app\common\model\User','id','user_id');
    }


}