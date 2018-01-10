<?php
namespace frontend\controllers;

use backend\models\Member;

use frontend\models\MemberForm;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation=false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
            /*
            * 首页静态化，商品详情页静态化
            */
            $contents=$this->render('index');
            file_put_contents('index.html',$contents);
            return $this->render('@web/index.html');
    }
    

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionRegister(){
        $model=new MemberForm();
        $request=Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),'');
            //验证手机号和短信验证码
            $redis= new \Redis();
            $redis->open('127.0.0.1','6379');
            $phone=$redis->get($model->tel.'_number');
            $code=$redis->get('valid_'.$phone);
            if ($phone){
                if ($model->captcha==$code){
                    $user = $model->signup();
                    if ($user) {
                        //跳转
                        return $this->redirect(Url::to(['site/index']));
                    }
                }
                else{
                    var_dump('验证码不匹配');
                }

            }
            else{
                var_dump('手机号码不匹配');
            }
        }
        else{
            return $this->render('register');
        }
    }

    public function actionCheck($username){
        $row=Member::findAll(['username'=>$username]);
        if ($row){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function actionSms($phone){
        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        //短信防盗刷
        $ttl=$redis->ttl('valid_'.$phone);
        //25*60 < $ttl 距离上次发送不到一分钟
        if ($ttl && $ttl>29*60){
            return Json::encode(['status'=>'抱歉短信需要在'.(1800-$ttl).'分钟后才能重新发送']);
        }
        //对手机号正则验证
        $pattern='/^1\d{10}$/';
        $result=preg_match($pattern,$phone);

        if ($result){
                $valid=mt_rand(1000,9999);
                $res=Yii::$app->sms->send($phone,$valid);
                if ($res->Code=="OK"){
                    //发送成功
                    //将验证码存入redis或者session

                    $redis->set('valid_'.$phone,$valid,30*60);
                    //存入当前手机号码
                    $redis->set($phone.'_number',$phone,30*60);
                    return Json::encode(['status'=>'true']);
                }else{
                    //发送失败
                    return Json::encode(['status'=>'发送短信失败,请联系我们的客服']);
                }
        }else{
            return Json::encode(['status'=>'手机号码格式不正确']);
        }

    }

    //验证手机验证码
    public function actionValid($tel,$captcha){
        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        $valid=$redis->get('valid_'.$tel);
        if ($valid){
            if ($valid==$captcha){
                return 'true';
            }else{
                return 'false';
            }
        }else{
            return 'false';
        }
    }
}
