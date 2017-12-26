<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\Url;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=ArticleCategory::find()->where(['status' => [0,1]]);

        //分页
        $pager=new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => 7,
        ]);
        $rows=$model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','新增文章分类成功');
                return $this->redirect(Url::to(['article-category/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        $model->status=0;
        return $this->render('add',['model'=>$model]);
    }

    public function actionUpdate($id){
        $model=ArticleCategory::findOne($id);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改文章分类成功');
                return $this->redirect(Url::to(['article-category/index']));
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionDelete($id){
        $row=ArticleCategory::findOne($id);
        if ($row){
            $row->status=-1;
            $row->save();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

}
