<?php

namespace frontend\controllers;

use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * 错误页面提示跳转
     * @param string $jumpUrl   跳转地址
     * @param string $message   跳转信息
     * @param int $time         跳转时间
     */
    public function errorJump($jumpUrl = '', $message = '操作成功', $time = 2)
    {
        $str = '<!DOCTYPE HTML>';
        $str .= '<html>';
        $str .= '<head>';
        $str .= '<meta charset="utf-8">';
        $str .= '<title>页面提示</title>';
        $str .= '<style type="text/css">';
        $str .= '*{margin:0; padding:0}a{color:#369; ;text-decoration:none;}a:hover{text-decoration:underline}body{height:100%; font:12px/18px Tahoma, Arial,  sans-serif; color:#424242; background:#FFFFF5}.message{width:450px; height:120px; margin:16% auto; border:1px solid #99b1c4; background:#ecf7fb}.message h3{height:28px; line-height:28px; background:#2c91c6; text-align:center; color:#fff; font-size:14px}.msg_txt{padding:10px; margin-top:8px}.msg_txt h4{line-height:26px; font-size:14px}.msg_txt h4.red{color:#f30}.msg_txt p{line-height:22px}';
        $str .= '</style>';
        $str .= '</head>';
        $str .= '<body>';
        $str .= '<div class="message">';
        $str .= '<h3>操作提示</h3>';
        $str .= '<div class="msg_txt">';
        $str .= '<h4 class="red">' . $message . '</h4>';
        $str .= "<p>系统将在 <span style='color:blue;font-weight:bold'>$time</span> 秒后自动跳转,如果不想等待,直接点击 <a href='$jumpUrl'>这里</a> 跳转</p>";
        $str .= "<script>setTimeout(function() {
  location.replace('$jumpUrl');
},$time+'000')</script>";
        $str .= '</div>';
        $str .= '</div>';
        $str .= '</body>';
        $str .= '</html>';
        echo $str;
    }

}