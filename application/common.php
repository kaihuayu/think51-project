<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//公共函数库 过滤文章摘要
if (!function_exists('checkConten')) {

    function checkConten($contene)
    {
        return mb_substr(strip_tags($contene), 0, 30) . '......详细';
    }
}

if (!function_exists('getCatname')){


}