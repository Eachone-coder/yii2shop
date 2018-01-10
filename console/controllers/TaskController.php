<?php
namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\console\Controller;

class TaskController extends Controller{
    public function actionClean(){
        set_time_limit(0);
        while (true){
            /*
            先查出所有过期且状态为待支付的订单 time() - create_time >  24*3600  =>create_time <time()-24*3600
            */
            $orders=Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-24*3600])->all();
            foreach ($orders as $order){
                $order->status=0;
                $order->save();
                $goods=OrderGoods::find()->where(['order_id'=>$order->id])->all();
                foreach ($goods as $good){
                    Goods::updateAllCounters(['stock'=>$good->amount],['id'=>$good->goods_id]);
                }
            }
            echo iconv('utf-8','gbk','清理完成').date('H:i:s');
            echo "\n";
            //一秒执行一次
            sleep(4);
        }
    }
}