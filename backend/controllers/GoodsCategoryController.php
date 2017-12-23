<?php
namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\Url;

class GoodsCategoryController extends \yii\web\Controller {
    public function actionIndex(){
        $model=GoodsCategory::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
               if ($model->parent_id){
                   $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                   $model->appendTo($parent);
               }else{
                   $model->makeRoot();
               }
                \Yii::$app->session->setFlash('success','新增商品分类成功');
                return $this->redirect(Url::to(['goods-category/index']));
            }
        }
        return $this->render('alter',['model'=>$model]);
    }

    public function actionUpdate($id){
        $model=GoodsCategory::findOne($id);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->appendTo($parent);
                }else{
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','新增商品分类成功');
                return $this->redirect(Url::to(['goods-category/index']));
            }
        }
        return $this->render('alter',['model'=>$model]);
    }

    public function actionDelete($id){

    }

}