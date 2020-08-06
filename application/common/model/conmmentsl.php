<?php


namespace app\common\model;
use think\Model;

class conmmentsl extends Model
{
    protected $pk = "id"; //表的主键默认
    protected  $table  ="zh_user_comments";//模型绑定的表
    protected  $autoWriteTimestamp = true; //自动时间戳
    protected  $createTime ='create_time'; //创建时间字段
    protected  $updateTime ='update_time'; //更新时间字段
    protected  $dateFormat ='Y年m月d日';

    //仅更新是设置
    protected  $update =['update_time'];
}