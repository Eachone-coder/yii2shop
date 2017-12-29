<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;

    //场景
    const SCENARIO_ADD_PERMISSION = 'add-permission';       //添加场景
    const SCENARIO_EDIT_PERMISSION = 'edit-permission';     //修改场景

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','checkName','on'=>[self::SCENARIO_ADD_PERMISSION]],
            ['name','validName','on'=>[self::SCENARIO_EDIT_PERMISSION]],
            ['name','match','pattern' => '/^\w+-?\w+\/\w+-?\w+$/','message' => '权限名填写出错,请填写正确格式,如  user/add']
        ];
    }

    //添加场景的验证
    public function checkName(){
        //根据输入的名字查找表
        $authManager=\Yii::$app->authManager;
        if ($authManager->getPermission($this->name)){
            $this->addError('name','已存在相同的权限名');
        }
    }

    //修改场景的验证
    public function validName()
    {
        $authManager=\Yii::$app->authManager;
        //先获取就的名字   -->get
        $oldName=\Yii::$app->request->get('name');
        //如果那么发生变化并且已存在,提示错误
        if (($this->name!=$oldName) && $authManager->getPermission($this->name)){
            $this->addError('name','已存在相同的权限名');
        }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'描述',
        ];
    }
}