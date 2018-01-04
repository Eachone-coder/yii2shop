<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>登录商城</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/login.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
    </div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10">
    <div class="login_hd">
        <h2>用户登录</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <form action="<?php echo \yii\helpers\Url::to(['login/index'])?>" method="post" id="signupForm">
                <ul>
                    <li>
                        <label for="username">用户名：</label>
                        <input type="text" class="txt" name="username" id="username"/>
                        <p class="help-block help-block-error"></p>
                    </li>
                    <li>
                        <label for="password">密码：</label>
                        <input type="password" class="txt" name="password" id="password"/>
                        <a href="">忘记密码?</a>
                        <p class="help-block help-block-error"></p>

                    </li>
                    <li class="checkcode">
                        <label for="checkcode">验证码：</label>
                        <input type="text"  name="checkcode" id="checkcode"/>
                        <img src="" alt="" id="img"/>
                        <span>看不清？<a href="javascript:;" id="changeCode">换一张</a></span>
                        <p class="help-block help-block-error"></p>
                    </li>
                    <li>
                        <label for="rememberMe">&nbsp;</label>
                        <input type="checkbox" class="chb" value="1" name="rememberMe" id="rememberMe"/> 保存登录信息
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit"  value="" class="login_btn" />
                    </li>
                </ul>
            </form>

            <div class="coagent mt15">
                <dl>
                    <dt>使用合作网站登录商城：</dt>
                    <dd class="qq"><a href=""><span></span>QQ</a></dd>
                    <dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
                    <dd class="yi"><a href=""><span></span>网易</a></dd>
                    <dd class="renren"><a href=""><span></span>人人</a></dd>
                    <dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
                    <dd class=""><a href=""><span></span>百度</a></dd>
                    <dd class="douban"><a href=""><span></span>豆瓣</a></dd>
                </dl>
            </div>
        </div>

        <div class="guide fl">
            <h3>还不是商城用户</h3>
            <p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

            <a href="regist.html" class="reg_btn">免费注册 >></a>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript" src="/jquery-validation/lib/jquery-3.1.1.js"></script>
<script type="text/javascript" src="/jquery-validation/dist/jquery.validate.js"></script>
<script type="text/javascript" src="/jquery-validation/dist/localization/messages_zh.js"></script>
<script type="text/javascript">
    var hash='';
    //切换验证码
    $('#changeCode').click(function () {
        $.getJSON('<?php echo \yii\helpers\Url::to(['site/captcha'])?>',{'refresh':1},function (data) {
            $('#img').attr('src',data.url);
            hash=data.hash1;
        });
        return false;
    });
    $('#changeCode').click();

    //自定义验证验证码
    jQuery.validator.addMethod("code", function(value, element) {
        var v=value.toLowerCase();  //将用户输入的验证码转换为小写
        var h=0;
        var length=v.length-1;
        for (var i=length;i>=0;--i){
            h+=v.charCodeAt(i);
        }
        return h==hash;
    }, "请正确填写您的验证码");

    //jquery-validate基础验证
    $("#signupForm").validate({
        rules: {

            username: {
                required: true,
                minlength: 0
            },
            password: {
                required: true,
                minlength: 0
            },
            checkcode: {
                code: true,
                minlength: 0
            }
        },
        messages: {
            username: {
                required: "请输入用户名",
                minlength: "用户名必需由两个字母组成"
            },
            password: {
                required: "请输入密码",
                minlength: "密码长度不能小于 0 个字母"
            }
        },
        errorElement:'span'
    })
</script>
</body>
</html>