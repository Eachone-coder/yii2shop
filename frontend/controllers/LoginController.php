<?php
namespace frontend\controllers;

use light\widgets\SweetSubmitAsset;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\models\LoginForm;
use yii\web\JqueryAsset;


class LoginController extends Controller{
    public $enableCsrfValidation=false;
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex(){
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post(),'')) {
            if ($model->validate()){
                //调用model的check()方法
                $result=$model->check();
                if ($result=='true'){
                    //跳转
                    return $this->redirect(Url::to(['site/index']));
                }else if ($result=='1'){
                   $model->addError('username','用户名不存在');
                }else{
                    $model->addError('password','密码不正确');
                }
            }else{
                var_dump($model->getErrors());
            }
        }
            return $this->render('login');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(Url::to(['site/index']));
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}