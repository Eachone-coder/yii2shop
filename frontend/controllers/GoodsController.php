<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\Goods;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller{
    public $enableCsrfValidation=false;
    /**
     * 商品三级分类查询
     * @param $id
     * @return string
     */
    public function actionGoodsCategory($id){
        $row=GoodsCategory::findOne(['id'=>$id]);
        if ($row->depth==2){
            $ids=$row->id;
        }else{
            $ids=$row->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->all();
            $ids=ArrayHelper::map($ids,'id','id');
        }
        $goods=Goods::find()->where(['in','goods_category_id',$ids])->all();
        return $this->render('list',['goods'=>$goods]);
    }

    /**
     * 商品具体详情
     * @param $id
     * @return string
     */
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        $goodsGallery=GoodsGallery::findAll(['goods_id'=>$id]);
        $first=array_shift($goodsGallery);
        return $this->render('goods',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'goodsGallery'=>$goodsGallery,'first'=>$first]);
    }


    /**
     * @return string
     */
    public function actionSearch(){
        $request=\Yii::$app->request;
        //var_dump($request->get('keywords'));
        $goods=Goods::find()->where(['LIKE','name',$request->get('keywords')])->all();
        return $this->render('list',['goods'=>$goods]);
    }
}