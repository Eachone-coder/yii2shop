<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li><?php if (!Yii::$app->user->isGuest){
                        echo '欢迎回来![<a href="">'.Yii::$app->user->identity->username.'个人中心</a>][<a href="'.\yii\helpers\Url::to(['login/logout']).'">退出</a>]';
                    }else{
                        echo '您好，欢迎来到岛上书店！[<a href="'.\yii\helpers\Url::to(['login/index']).'">登录</a>';
                    }?>] [<a href="<?php echo  \yii\helpers\Url::to(['site/register'])?>">免费注册</a>] </li>
                <li class="line">|</li>
                <li><a href="<?=\yii\helpers\Url::to(['goods/order-list'])?>">我的订单</a></li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->