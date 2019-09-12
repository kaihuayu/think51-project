<?php
/*
 * 基础控制器
 * 基础控制器公共 继承自 think\controller
 * 其他控制就直接继承 Base  就可以了 不用继承Controller
 */

namespace app\common\controller;
use think\controller;

class Base extends Controller
{
    /*
 * 初始化方法
 * 创建常量，公共方法
 * 在所有方法之前被调用

*/
    protected function initialize(){

    }
}