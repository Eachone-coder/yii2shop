<?php

namespace backend\controllers;

use backend\models\User;
use yii\data\Pagination;
use yii\helpers\Url;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=User::find();
        $pager=new Pagination(['totalCount' => $query->count(),'pageSize' => 5]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    public function actionAdd(){
        $model=new User();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->password==$model->password_hash){
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                    $model->save(false);
                    \Yii::$app->session->setFlash('success','新增用户名成功');
                    return $this->redirect(Url::to(['user/index']));
                }
                else{
                    var_dump($model->getErrors());
                }
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionUpdate($id){
        $model=User::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->password==$model->password_hash){
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                    $model->save(false);
                    \Yii::$app->session->setFlash('success','新增用户名成功');
                    return $this->redirect(Url::to(['user/index']));
                }
                else{
                    var_dump($model->getErrors());
                }
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('update',['model'=>$model]);
    }
    public function actionDelete($id){

    }

}
