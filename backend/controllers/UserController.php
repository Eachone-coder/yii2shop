<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\UserForm;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;

class UserController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $query=User::find()->where(['status'=>1]);
        $pager=new Pagination(['totalCount' => $query->count(),'pageSize' => 5]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionAdd(){
        $model=new User();
        $request=\Yii::$app->request;
        //角色
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRoles();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                //存入角色和管理员的关系
                if ($model->roles){
                    foreach ($model->roles as $name){
                        $role=$authManager->getRole($name);
                        $authManager->assign($role,$model->getId());
                    }
                }
                \Yii::$app->session->setFlash('success','新增用户名成功');
                return $this->redirect(Url::to(['user/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        $model->status=0;
        return $this->render('add',['model'=>$model,'roles'=>$roles]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id){
        $model=UserForm::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        //角色
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRoles();
        //回显
        $roleName=$authManager->getRolesByUser($id);
        foreach ($roleName as $name){
            $model->roles[]=$name->name;
        }
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                /*
                >>1.旧密码为空,不做密码的修改
                >>2.旧密码不为空,调用验证密码的方法,
                */
                if ($model->oldPassword!=null){
                    if ($model->checkPwd()){
                        $model->password_hash=\Yii::$app->security->generatePasswordHash($model->newPassword);
                        $model->save();
                        //存入角色和管理员的关系
                        $authManager->revokeAll($id);
                        if ($model->roles){
                            foreach ($model->roles as $name){
                                $role=$authManager->getRole($name);
                                var_dump($role);die;
                                $authManager->assign($role,$id);
                            }
                        }
                        \Yii::$app->session->setFlash('success','修改用户名成功');
                        return $this->redirect(Url::to(['user/index']));
                    }
                }
                $model->save();
                //存入角色和管理员的关系
                $authManager->revokeAll($id);
                if ($model->roles){
                    foreach ($model->roles as $name){
                        $role=$authManager->getRole($name);
                        var_dump($role);die;
                        $authManager->assign($role,$id);
                    }
                }
                \Yii::$app->session->setFlash('success','修改用户名成功');
                return $this->redirect(Url::to(['user/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('update',['model'=>$model,'roles'=>$roles]);
    }

    /**
     * @param $id
     */
    public function actionDelete($id){
        $authManager=\Yii::$app->authManager;
        $row=User::findOne(['id'=>$id]);
        if ($row){
            $authManager->revokeAll($id);
            $row->status=0;
            $row->save();
            echo Json::encode(['status'=>$id]);
        }
        else{
            echo Json::encode(['status'=>'删除失败']);
        }
    }

}
