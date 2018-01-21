<?php
namespace console\controllers;

use backend\models\Goods;
use yii\console\Controller;

class ViewsController extends Controller{
    public function actionSynchronize($max){
        //set_time_limit(0);
        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        /*
        脚本
        */
        $id=1;
        while($id<$max){
            $views=$redis->get('views_'.$id);
            if ($views){
                Goods::updateAll(['view_times'=>$views],['id'=>$id]);
            }
            $id+=1;
        }
        echo iconv('utf-8','gbk','同步完成').date('Y-m-d H:i:s');
        echo "\n";
    }
}