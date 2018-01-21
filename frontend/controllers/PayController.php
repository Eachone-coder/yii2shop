<?php
namespace frontend\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Payment\Order;
use EasyWeChat\Foundation\Application;
use yii\web\HttpException;
use Endroid\QrCode\QrCode;

class PayController extends Controller{
    public function actionPay($id){
        $model=\frontend\models\Order::findOne(['id'=>$id]);
        //检查订单状态
        if (!$model || $model->status!=1){
            exit;
        }
        //配置
        $options=\Yii::$app->params['wechat'];

        $app = new Application($options);

        $payment = $app->payment;
        //1.生成微信支付订单
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => $model->trade_no,
            'total_fee'        => 5388, // 单位：分
            'notify_url'       => Url::to(['pay/notify'],1), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];

        $order = new Order($attributes);

        //2 调统一下单api接口
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            $code_url = $result->code_url;
            return $this->renderPartial('pay',['code_url'=>$code_url,'order'=>$order]);
        }else{
            throw new HttpException(500,'获取支付信息失败');
        }
    }
    //生成二维码
    public function actionQrcode($content){
        $qrCode = new QrCode($content);
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }
    //支付结果通知
    public function actionNotify(){
        $options = \Yii::$app->params['wechat'];

        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = \frontend\models\Order::findOne(['trade_no'=>$notify->out_trade_no]);//($notify->out_trade_no);

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            /*if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }*/

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 2;//状态修改成待发货
                $order->save(); // 保存订单
            } else { // 用户支付失败
                //$order->status = 'paid_fail';
            }



            return true; // 返回处理完成
        });

        $response->send();
    }


    public function actionTest(){
        //var_dump(Url::to(['pay/notify'],1));
        $orderNo = "3368018";
        $options = \Yii::$app->params['wechat'];

        $app = new Application($options);

        $payment = $app->payment;
        $result = $payment->query($orderNo);
        var_dump($result);
    }
}