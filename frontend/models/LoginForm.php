<?php
namespace frontend\models;

use frontend\models\Member;
use yii\base\Model;

class LoginForm extends Model{
        public $username;
        public $password;
        public $rememberMe;
        public $checkcode;

        public function rules()
        {
            return [
                [['username','password'],'required'],
                ['rememberMe','safe'],
                ['checkcode','captcha','captchaAction' => 'site/captcha'],
            ];
        }
        public function check(){
            $member=Member::findOne(['username'=>$this->username]);
            //先判断名字,在验证密码
            if ($member){
                //名字存在
                //验证密码
                if (\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                    $time='';
                    if ($this->rememberMe){
                        $time=3600;
                    }
                    \Yii::$app->user->login($member,$time);
                    $member->last_login_ip=\Yii::$app->getRequest()->getUserIP();
                    $member->last_login_time=time();
                    $member->save();
                    return 'true';
                }else{
                    return '2';
                }
            }
            else{
                return '1';
            }
        }
}