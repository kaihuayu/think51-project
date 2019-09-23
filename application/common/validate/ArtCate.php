<?php


namespace app\common\validate;


use think\Validate;

class ArtCate extends Validate

{
    protected  $rule =
[
    'name||标题'=>'require|chsAlphaNum|legth:10,80',
    ];

}