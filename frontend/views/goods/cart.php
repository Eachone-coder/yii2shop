<?php
/**
 * @var $this \yii\web\View
 */
echo $this->render('@webroot/public/top');
echo $this->render('@webroot/public/login');
echo '<link rel="stylesheet" href="/style/cart.css" type="text/css">';
echo '<script type="text/javascript" src="/js/cart1.js"></script>';
?>

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $model):?>
            <tr data-id="<?php echo $cart[$model->id]?>" id="cart<?php echo $cart[$model->id]?>" p-id="<?=$model->id?>">
            <td class="col1"><a href=""><img src="<?=$model->logo?>" alt="" /></a>  <strong><a href=""><?=$model->name?></a></strong></td>
            <td class="col3">￥<span><?=$model->shop_price?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$count[$model->id]?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span><?=$model->shop_price*$count[$model->id]?></span></td>
            <td class="col6"><a href="" class="btn-danger">删除</a></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">1870.00</span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <a href="<?=\yii\helpers\Url::to(['goods/order'])?>" class="checkout">结 算</a>
    </div>
</div>
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
<script type="text/javascript">
    $(function() {
        //修改库存 start

        $(".reduce_num,.add_num").click(function () {
            var id=$(this).closest("tr").attr("data-id");
            var amount=$(this).closest('tr').find('input').val();
            var pid=$(this).closest("tr").attr("p-id");
            console.debug(pid);
            $.getJSON("<?=\yii\helpers\Url::to(['goods/edit-amount'])?>",{"id":id,"amount":amount,'pid':pid},function (data) {
                //console.debug(data);
            });
        });


        $(".amount").blur(function () {
            var id=$(this).closest('tr').attr('data-id');
            var amount=$(this).closest('tr').find('input').val();
            var patrn = /^[1-9][0-9]*$/;
            var pid=$(this).closest("tr").attr("p-id");
            if (patrn.exec(amount) == null || amount == ""){
                alert('检查到你输入的数字不合法,输入值为: 　'+amount);
            }
            else{
                $.getJSON('<?php echo \yii\helpers\Url::to(['goods/edit-amount'])?>',{'id':id,'amount':amount,'pid':pid},function (data) {
                    console.debug(data.status);
                    if (!data.status=='true'){
                        alert('检查到你输入的数字不合法,输入值为: 　'+amount);
                    }

                });
            }
        });
        //修改库存 end

        //删除    start
        $('.btn-danger').click(function () {
            if (confirm('确定删除?')){
                var id=$(this).closest('tr').attr('data-id');
                var pid=$(this).closest("tr").attr("p-id");
                $.getJSON('<?php echo \yii\helpers\Url::to(['goods/del-amount'])?>',{'id':id,'pid':pid},function () {
                   var name='#cart'+id;
                   $(name).remove();
                });
            }
            return false;
        });
        //删除    end
    });
</script>
</body>
</html>
