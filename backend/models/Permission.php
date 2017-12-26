<?php
namespace backend\models;

use yii\base\Model;

class Permission extends Model{
    public $name;
    public $description;

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','checkName']
        ];
    }

    public function checkName(){
        //根据输入的名字查找表
        $authManager=\Yii::$app->authManager;
        if ($authManager->getPermission($this->name)){
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