<?php
namespace frontend\models;

use backend\models\Member;
use yii\base\Model;

class LoginForm extends Model{
        public $username;
        public $password;

        public function rules()
        {
            return [
                [['username','password'],'required']
            ];
        }
        public function check(){
            $member=Member::findOne(['username'=>$this->username]);
            //先判断名字,在验证密码
            if ($member){
                //名字存在
                //验证密码
                if (\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                    \Yii::$app->user->login($member,3600);
                    $member->last_login_ip=\Yii::$app->getRequest()->getUserIP();
                    $member->last_login_time=time();
                    $member->save();
                    return true;
                }else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
}