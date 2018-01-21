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
                ['checkcode','captcha','captchaAction' => 'site/captcha','message' => '验证码不正确'],
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
                    //读cookie
                    $cookie=\Yii::$app->request->cookies;
                    $data=$cookie->getValue('cart');
                    $datas=unserialize($data);
                    if ($datas){
                        $ids=array_keys($datas);
                        /*
                         * [goods_id=>amount,goods_id=>amount,...]
                        思路,需要将已存在的没未存在的分开
                        */
                        $row=[];
                        foreach ($ids as $id){
                            $row=Cart::findOne(['goods_id'=>$id,'member_id'=>\Yii::$app->user->identity->getId()]);
                            if ($row){
                                $row->amount+=$datas[$id];
                            }
                            else{
                                $row=new Cart();
                                $row->amount=$datas[$id];
                                $row->member_id=\Yii::$app->user->identity->getId();
                                $row->goods_id=$id;
                            }
                            if ($row->validate()){
                                $row->save();
                            }else{
                                $row->getErrors();
                            }
                        }
                        //删除cookie
                        $writeCookie=\Yii::$app->response->cookies;
                        $writeCookie->remove('cart');
                    }

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