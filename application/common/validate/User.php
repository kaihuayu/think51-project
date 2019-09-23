<?php


namespace app\common\validate;
/*
 * zh_user 表的验证器
 * */
use think\Validate;
class User extends Validate
{
    protected $rule =[

        'name|姓名'=>[
            'require'=>'require',
            'max'     => 20,
            'min'     => 6,
            'length'=>'5,20',
           'chsAlphaNum '=>'chsAlphaNum' //仅允许汉字，字母和数字
        ],
        'email|邮箱'=>[
            'require'=>'require',
            'email'=>'email',
            'unique' =>'zh_user',//该字段必须在 表中是唯一,不能重复

        ],
        'password|密码'=>[
            'require'=>'require',
            'min'=>6,
            'max'=>12,
            'alphaNum' ,//字母加数字
            'confirm'=>'password-confirm',//重复检查二次输入
        ],
      /*  'mobile|手机'=>[
            'require'=>'require',
            'mobile',
            'number',
            'alphaNum',
            'unique'=>'unique',
                'number'=>'number'

        ],*/

        'mobile|手机'=>'require|mobile|unique:zh_user',

    ];
}