<?php
namespace frontend\controllers;

use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Goods;
use yii\web\Controller;

class GoodsController extends Controller{
    public function actionGoodsCategory($id){
        $goods=Goods::findAll(['goods_category_id'=>$id]);
        return $this->render('list',['goods'=>$goods]);
    }
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        $goodsGallery=GoodsGallery::findAll(['goods_id'=>$id]);
        return $this->render('goods',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'goodsGallery'=>$goodsGallery]);
    }
}