<?php


namespace app\common\model;


use think\Model;

class Site extends Model
{
    protected $pk = "id"; //表的主键默认
    protected  $table  ="zh_site";//模型绑定的表
}