<?php
namespace backend\models;

use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $varCode;
    public $remeberMe;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['varCode', 'captcha', 'captchaAction' => 'login/captcha',],
            ['remeberMe','safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'varCode' => '验证码',
            'remeberMe' => '记住我',
        ];
    }

    /**
     * @return bool
     */
    public function check()
    {
        /*
                先根据用户名查询,在判断密码
        user 认证类和用户组件 yii\web\User。前者是实现 认证逻辑的类，
        通常用关联了 持久性存储的用户信息的AR模型 Active Record 实现。后者是负责管理用户认证状态的 应用组件。
               */
        $user = User::findOne(['username' => $this->username]);
        if ($user) {
            //判断密码
            if (\Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                //密码匹配
                //>>1.存入登录时间和ip
                $user->last_login_time=time();
                $user->last_login_ip = ($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["SERVER_ADDR"];
                $user->save();
                //>>.2存入session
                \Yii::$app->user->login($user);
                if ($this->remeberMe){
                    //>>.3存入cookie  存入id和密码
                    //写cookie
                    $responseCookie=\Yii::$app->response->cookies;
                    $writeCookie=new Cookie();
                    $writeCookie->name='name';
                    $writeCookie->value=$user->username;
                    $writeCookie->expire=time()+60*2;
                    $responseCookie->add($writeCookie);
                    $writeCookie->name='password';
                    $writeCookie->value=$user->auth_key;
                    $writeCookie->expire=time()+60*2;
                    $responseCookie->add($writeCookie);
                }
                return true;
            } else {
                $this->addError('password', '密码不正确');
                return false;
            }
        } else {
            $this->addError('username', '用户名不存在');
            return false;
        }
    }
    /*public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=>['last_login_time'],
                ],
            ],
        ];
    }*/
}