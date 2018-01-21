<h1>微信支付订单</h1>
<p>订单编号：<?=$order->out_trade_no?></p>

<p>金额:<?=$order->total_fee?>分</p>
<p>微信支付</p>

<img src="<?=\yii\helpers\Url::to(['pay/qrcode',['content'=>$code_url]])?>" />