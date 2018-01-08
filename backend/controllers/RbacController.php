<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\rbac\Role;
use yii\web\Controller;

class RbacController extends BaseController{
    public function actionIndex()
    {
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermissions();
        return $this->render('index-permission',['rows'=>$permission]);
    }
    public function actionAddPermission(){
        $model=new PermissionForm();
        $request=\Yii::$app->request;
        //创建一个权限对象
        $authManager= \Yii::$app->authManager;
        //判断数据
        $permission=new \yii\rbac\Permission();
        $model->scenario=PermissionForm::SCENARIO_ADD_PERMISSION;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){

                $permission->name=$model->name;
                $permission->description=$model->description;
                //保存
                $authManager->add($permission);
                \Yii::$app->session->setFlash('success','新增权限成功');
                return $this->redirect(Url::to(['rbac/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }

    public function actionEditPermission($name){
        $model=new PermissionForm();
        //创建一个权限对象
        $authManager= \Yii::$app->authManager;
        $request=\Yii::$app->request;
        $model->scenario=PermissionForm::SCENARIO_EDIT_PERMISSION;
        $permission=$authManager->getPermission($name);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //保存
                //判断数据
                $permission->name=$model->name;
                $permission->description=$model->description;
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect(Url::to(['rbac/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        $model->name=$permission->name;
        $model->description=$permission->description;
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionDelPermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        $authManager->remove($permission);
        echo Json::encode(['status'=>1]);
    }

    public function actionIndexRole()
    {
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRoles();
        return $this->render('index-role',['rows'=>$role]);
    }

    public function actionAddRole()
    {
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_ADD_ROLE;
        $authManager=\Yii::$app->authManager;
        $list=$authManager->getPermissions();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $role= new \yii\rbac\Role;
                $role->name=$model->name;
                $role->description=$model->description;
                $authManager->add($role);
                //>>给角色赋予权限
                if ($model->permissions==null){
                    $model->permissions=[];
                }
                foreach ($model->permissions as $val){
                    $permission = $authManager->getPermission($val);
                    $authManager->addChild($role,$permission);
                }
                \Yii::$app->session->setFlash('success','新增角色成功');
                return $this->redirect(Url::to(['rbac/index-role']));
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add-role',['model'=>$model,'list'=>$list]);
    }

    public function actionEditRole($name)
    {
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_EDIT_ROLE;
        $authManager=\Yii::$app->authManager;
        $list=$authManager->getPermissions();
        $request=\Yii::$app->request;
        $role=$authManager->getRole($name);

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $role->name=$model->name;
                $role->description=$model->description;
                $authManager->update($name,$role);
                //>>给角色赋予权限
                //取消关联所有权限
                $authManager->removeChildren($role);
                if ($model->permissions==null){
                    $model->permissions=[];
                }
                foreach ($model->permissions as $val){
                    $permission = $authManager->getPermission($val);
                    $authManager->addChild($role,$permission);
                }
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect(Url::to(['rbac/index-role']));
            }else{
                var_dump($model->getErrors());
            }
        }
        $model->name=$role->name;
        $model->description=$role->description;
        $rows=$authManager->getPermissionsByRole($role->name);
        $model->permissions=[];
        foreach ($rows as $row){
            $model->permissions[]=$row->name;
        }
        return $this->render('add-role',['model'=>$model,'list'=>$list]);
    }
    public function actionDelRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        $authManager->remove($role);
        echo Json::encode(['status'=>1]);
    }
}