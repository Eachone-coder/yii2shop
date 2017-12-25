<?php

namespace backend\controllers;

use backend\models\User;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;

class UserController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $query=User::find()->where(['status'=>1]);
        $pager=new Pagination(['totalCount' => $query->count(),'pageSize' => 5]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionAdd(){
        $model=new User();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            var_dump($model);die;
            if ($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                \Yii::$app->session->setFlash('success','新增用户名成功');
                return $this->redirect(Url::to(['user/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
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

    /**
     * @param $id
     */
    public function actionDelete($id){
        $row=User::findOne(['id'=>$id]);
        if ($row){
            $row->status=0;
            $row->save();
            echo Json::encode(['status'=>$id]);
        }
        else{
            echo Json::encode(['status'=>'删除失败']);
        }
    }

}
