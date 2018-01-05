<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Member;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class MemberController extends Controller{
    public $enableCsrfValidation=false;

    /**
     * 个人收货地址
     * @param string $id
     * @return string
     */
    public function actionAddress($id=''){
        $row=[];
        if ($id){
            $row=Address::findOne(['id'=>$id]);
        }
        return $this->render('address',['row'=>$row]);
    }

    /**
     * 列表显示
     * @return string
     */
    public function actionList(){
        $rows=Address::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->orderBy('id')->all();
        return Json::encode(['data'=>$rows]);
    }

    /**
     * 新增收货地址
     * @return string
     */
    public function actionAddAddress(){
        /*
        >>1.先判断是否有值传过来
        >>2.绑定数据
        >>3.验证数据
        >>4.保存
        */
        if (\Yii::$app->request->post()){
            $model=new Address();
            $data=\Yii::$app->request->post('form');
            $array=ArrayHelper::map($data,'name','value');
            $model->load($array,'');
            if ($model->validate()){
                $model->save();
                $id=\Yii::$app->db->getLastInsertID();
                return Json::encode(['data'=>$array,'id'=>$id]);
            }else{
                return Json::encode(['status'=>$model->getErrors()]);
            }
        }
    }

    /**
     * @return string
     */
    public function actionEditAddress()
    {
        $row=Address::findOne(['id'=>\Yii::$app->request->post('id')]);
        $data=\Yii::$app->request->post('form');
        $array=ArrayHelper::map($data,'name','value');
        $row->load($array,'');
        if ($row->validate()){
            Address::updateAll(['is_default'=>0],['member_id'=>\Yii::$app->user->identity->getId()]);
            $row->save();
            return Json::encode(['data'=>$array,'id'=>$row->id]);
        }else{
            return Json::encode(['status'=>$row->getErrors()]);
        }
    }

    /**
     * 删除地址
     * @param $id
     * @return string
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelAddress($id){
        $row=Address::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            return Json::encode(['status'=>$id]);
        }
        else{
            return Json::encode(['status'=>0]);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionEditDefault($id){
        //先将所有的is_default设置为0
        Address::updateAll(['is_default'=>0],['member_id'=>\Yii::$app->user->identity->getId()]);
        //设置这个对应的ID的is_default为1
        Address::updateAll(['is_default'=>1],['id'=>$id]);
        return Json::encode(['status'=>1]);
    }
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules' => [
                    [
                        'allow'=>true,
                        'actions'=>['address','list','add-address','edit-address','del-address','edit-default'],
                        'roles'=>['@']       //  ?代表未认证的用户,  @标识已认证的用户
                    ],
                ],
            ],
        ];
    }
}