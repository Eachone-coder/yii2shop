<?php
namespace frontend\controllers;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;

class ArticleController extends Controller{
    public function actionShow($id){
        //$this->layout=false;
        $model=Article::findOne($id);
        $sonModel=ArticleDetail::findOne($id);
        $category=ArticleCategory::find()->where(['status'=>[0,1]])->all();
        $category=ArrayHelper::map($category,'id','name');
        return $this->render('show',['model'=>$model,'sonModel'=>$sonModel,'category'=>$category]);
    }
}