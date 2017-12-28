<?php
namespace backend\models;

class UserForm extends User{

    public $oldPassword;    //旧密码
    public $rePassword;    //新密码
    public $newPassword;

    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword','status'],'safe'],
            [['username','email'],'required'],
            [['oldPassword','newPassword','rePassword'],'checkPwd'],
            ['roles','safe'],
        ];
    }

    public function checkPwd()
    {
        /*
        >>1.先根据id查找到具体的用户信息
        >>2.先判断旧密码是否正确      -->不正确,提示错误
        >>3.旧密码正确,判断新密码和确认密码是否为空        -->不正确,提示错误
        >>4.新密码和确认密码不为空,并且判断是否相等        -->不正确,提示错误
        >>5.相等,返回true,保存新密码
        */
        if ($this->oldPassword != null) {
            $user = User::findOne(['id' => $this->id]);
            if ($this->newPassword != null && $this->rePassword != null) {
                if ($this->newPassword == $this->rePassword) {
                    //判断旧密码是否正确
                    if (\Yii::$app->security->validatePassword($this->oldPassword, $user->getOldAttribute('password_hash'))) {
                        //验证成功
                        return true;
                    } else {
                        $this->addError('oldPassword', '旧密码不正确');
                        return false;
                    }
                } else {
                    $this->addError('rePassword', '两次输入密码不一致');
                    return false;
                }
            } else if ($this->newPassword == null) {
                $this->addError('newPassword', '请输入新密码');
                return false;
            } else if ($this->rePassword == null) {
                $this->addError('rePassword', '请输入确认密码');
                return false;
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'username'=>'姓名',
            'rePassword'=>'确认密码',
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'email' => '邮箱',
        ];
    }
}