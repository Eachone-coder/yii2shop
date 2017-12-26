<?php
namespace backend\models;

use yii\base\Model;

class Role extends Model{
    public $name;
    public $description;
    public $permissions;

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','checkName']
        ];
    }

    public function checkName(){
        //根据输入的名字查找表
        $authManager=\Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            $this->addError('name','已存在相同的角色名');
        }
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }
}