<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $varCode;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['varCode', 'captcha', 'captchaAction' => 'login/captcha',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'varCode' => '验证码',
        ];
    }

    public function check()
    {
        /*
                先根据用户名查询,在判断密码
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
//                var_dump(\Yii::$app->user->identity);
                \Yii::$app->user->login($user);
//                var_dump(\Yii::$app->user->identity);die;
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