<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\filter\RbacFilter;

class ArticleCategoryController extends \yii\web\Controller
{
    /**
     * 文章分类首页
     * @return string
     */
    public function actionIndex()
    {
        $model=ArticleCategory::find()->where(['status' => [0,1]]);
        //分页
        $pager=new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => 6,
        ]);
        $rows=$model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    /**
     * 文章分类添加
     * @return string|\yii\web\Response
     */
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

    /**
     * 文章分类修改
     * @param $id
     * @return string|\yii\web\Response
     */
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

    /**
     * 文章分类删除(逻辑删除)
     * @param $id
     */
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

    /**
     * 文章分类删除(强制删除)
     * @param $id
     */
    public function actionDel($id){
        $row=ArticleCategory::findOne($id);
        if ($row){
            $row->delete();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

    /**
     * RBAC权限控制
     * @return array
     */
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except' => ['index','logout','upload','captcha','ueditor'],
            ],
        ];
    }
}
