<?php
namespace frontend\models;

use frontend\models\Member;
use yii\base\Model;

class MemberForm extends Model{
    public $username;
    public $password;
    public $email;
    public $tel;
    public $checkcode;
    public $captcha;
    public function rules()
    {
        return [
            [['username',  'password', 'email', 'tel'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['password', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            [['checkcode','captcha'],'safe',]
        ];
    }

    /**
     * @return Member|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $member=new Member();
        $member->username=$this->username;
        $member->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
        $member->email=$this->email;
        $member->tel=$this->tel;
        $member->checkcode=$this->checkcode;
        if ($member->save()){
            \Yii::$app->user->login($member);
            return true;
        }
    }
}