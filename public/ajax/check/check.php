<?php
$user=['admin','jack','jane'];
$usr=empty($_POST) ? '':$_POST['user'];
$data =[
    'message'=>'用户已存在'
];
$data2 =[
    'message'=>'可以注册'
];
if (in_array($usr,$user)){  //检查勽 是否在 urer中存在
    echo json_encode($data);

}else{
    echo json_encode($data2);
}
//echo json_encode($_POST);