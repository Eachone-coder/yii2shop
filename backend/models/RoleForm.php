<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;

    //场景
    const SCENARIO_ADD_ROLE = 'add-role';
    const SCENARIO_EDIT_ROLE = 'edit-role';

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','checkName','on'=>[self::SCENARIO_ADD_ROLE]],
            ['name','validName','on'=>[self::SCENARIO_EDIT_ROLE]],
        ];
    }

    public function checkName(){
        //根据输入的名字查找表
        $authManager=\Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            $this->addError('name','已存在相同的角色名');
        }
    }

    public function validName()
    {
        $authManager=\Yii::$app->authManager;
        $oldName=\Yii::$app->request->get('name');
        if ($oldName!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','修改无效,该角色名已存在');
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