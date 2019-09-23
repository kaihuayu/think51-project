<?php
/*zh_user表的用户模型公共*/

namespace app\common\model;
use think\Model;

class User extends  Model
{
    protected $pk = "id"; //表的主键默认
    protected  $table  ="zh_user";//模型绑定的表
    protected  $autoWriteTimestamp = true; //自动时间戳
    protected  $createTime ='create_time'; //创建时间字段
    protected  $updateTime ='update_time'; //更新时间字段
    protected  $dateFormat ='Y年m月d日';
    //获取器
    //get字段名Attr 驼峰写法首字符大写
    public function getStatusAttr($value){
       $status =['1'=>'启用','0'=>'禁用'];
       return $status[$value];
    }
    public function getIsAdminAttr($value){
        $status =['1'=>'管理员','0'=>'普通会员'];
        return $status[$value];
    }
  //修改器  set字段名Attr 驼峰写法首字符大写 加密注册密码字段
    public function setPasswordAttr($value){
        return sha1($value); //加密密码字段
    }
}