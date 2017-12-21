<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class ArticleController extends Controller{
    public function actionIndex(){
        $model=Article::find()->where(['status'=>[0,1]]);

        $pager=new Pagination([
            'pageSize' => 5,
            'totalCount' => $model->count(),
        ]);
        $rows=$model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Article();
        $sonModel=new ArticleDetail();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $sonModel->load($request->post());
            if ($model->validate()){
                $model->create_time=strtotime($model->date_time);
                $sonModel->content=$model->details;
                $model->save();
                $sonModel->save();
                \Yii::$app->session->setFlash('success','新增文章成功');
                return $this->redirect(Url::to(['article/index']));
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('alter',['model'=>$model]);
    }
    public function actionUpdate($id){

    }
    public function actionDelete($id){}

}