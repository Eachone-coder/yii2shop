<?php
namespace backend\controllers;

use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;

class LoginController extends Controller{
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

    public function actionAuto(){
        $model=new LoginForm();
        //判断session,在判断cookie
        if (\Yii::$app->user->isGuest){
            //session中无用户信息
          $readCookie=\Yii::$app->request->cookies;
          if ($readCookie->has('name') && $readCookie->has('password')){
              //如果有,调用LoginForm的check方法
              if ($model->check()){
                  \Yii::$app->session->setFlash('success','登录成功');
                  return $this->redirect(['goods/index']);
                  //验证成功
              }
              else{
                  return $this->redirect(Url::to(['login/index']));
              }
          }else{
              return $this->redirect(Url::to(['login/index']));
          }
        }else{
            return $this->redirect(['goods/index']);
        }
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
    /*public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules' => [
                    [
                        'allow'=>true,
                        'actions'=>['index','captcha'],
                        'roles'=>['?','@']       //  ?代表未认证的用户,  @标识已认证的用户
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['log-out'],
                        'roles'=>['@']       //  ?代表未认证的用户,  @标识已认证的用户
                    ],
                ],
            ],
        ];
    }*/
}