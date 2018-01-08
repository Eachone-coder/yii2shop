<?php
/**
 * @var $this \yii\web\View
 */
echo $this->render('@webroot/public/top');
echo $this->render('@webroot/public/login');
echo '<link rel="stylesheet" href="/style/fillin.css" type="text/css">';
echo '<script type="text/javascript" src="/js/cart2.js"></script>';
?>

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="岛上书店"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<form action="<?=\yii\helpers\Url::to(['goods/order'])?>" method="post">
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">

    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($address as $value):?>
                    <p>
                        <input type="radio" value="<?=$value->id?>" name="address_id" <?=($value->is_default?'checked':'')?>/><?php echo $value->name;echo "&ensp;&ensp;";echo $value->tel;echo "&ensp;&ensp;";echo $value->cmbProvince;echo "&ensp;&ensp;";echo $value->cmbCity;echo "&ensp;&ensp;";echo $value->cmbArea;echo "&ensp;&ensp;";echo $value->address;?>
                    </p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (frontend\models\Order::$delivery as $k=>$v):?>
                    <tr <?=$k==1?'class="cur"':''?>>
                        <td><input type="radio" name="delivery_id" <?=$k==1?'checked':''?> value="<?=$k?>"/><?=$v[0]?></td>
                        <td><?=$v[1]?></td>
                        <td><?=$v[2]?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay" value="1" checked />货到付款</td>
                        <td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="2"/>在线支付</td>
                        <td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="3"/>上门自提</td>
                        <td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="4"/>邮局汇款</td>
                        <td class="col2">通过快钱平台收款 汇款后1-3个工作日到账</td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">

            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $price=0;
                foreach ($goods as $good):?>
                    <tr>
                        <td class="col1"><a href=""><img src="<?=$good->logo?>" alt="" /></a> <strong><a href=""><?=$good->name?></a></strong></td>
                        <td class="col3">￥<?=$good->shop_price?></td>
                        <td class="col4"><?=$amount[$good->id]?></td>
                        <td class="col5"><span>￥<?php echo $good->shop_price*$amount[$good->id];$price+=$good->shop_price*$amount[$good->id]?></span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span>4 件商品，总商品金额：</span>
                                <em id="total"><?=$price?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em id="back">3.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="costs">10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="money"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <button><span>提交订单</span></button>
        <p>应付总额：<strong id="m"></strong></p>
    </div>


</div>
</form>
<!-- 主体部分 end -->

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
        <a href="">岛上书店论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 岛上书店网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">

        $(".delivery_select input").click(function () {
            $('#costs').text($(this).closest('tr').find('td:eq(1)').text());
            var total=parseFloat($('#total').text());
            var back=parseFloat($('#back').text());
            var costs=parseFloat($('#costs').text());
            var mian=total-back+costs;
            $('#money').text('￥'+mian+'元');
            $('#m').text('￥'+mian+'元');
        });
</script>
</body>
</html>