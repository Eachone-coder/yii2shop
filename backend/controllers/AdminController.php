<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\User;
use backend\models\UserForm;
use yii\helpers\Url;
use yii\web\Controller;

class AdminController extends Controller{
    public function actionIndex()
    {
        //查邮箱
        $model=User::findOne(['id'=>\Yii::$app->user->identity->getId()]);

        return $this->render('index',['model'=>$model]);
    }

    public function actionEdit()
    {
        $model=UserForm::findOne(['id'=>\Yii::$app->user->identity->getId()]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                /*
                >>1.旧密码为空,不做密码的修改
                >>2.旧密码不为空,调用验证密码的方法,
                */
                if ($model->oldPassword!=null){
                    if ($model->checkPwd()){
                        $model->password_hash=\Yii::$app->security->generatePasswordHash($model->newPassword);
                        $model->save();
                        \Yii::$app->session->setFlash('success','修改用户名成功');
                        return $this->redirect(Url::to(['user/index']));
                    }
                }
                $model->save();
                \Yii::$app->session->setFlash('success','修改用户名成功');
                return $this->redirect(Url::to(['user/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('edit',['model'=>$model]);
    }

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