<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\web\Controller;

class LoginController extends BaseController{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            //验证数据
            if ($model->validate()){
                //调用模型中的check()方法验证用户名和密码是否正确
                if ($model->check()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['goods/index']);
                    //验证成功
                }
            }
        }
        return $this->render('index',['model'=>$model]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['login/index']);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4,
                'height' => 34,
                'padding' => 0
            ],
        ];
    }
}