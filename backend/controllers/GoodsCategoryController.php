<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\GoodsCategory;
use Qiniu\Http\Request;
use yii\helpers\Json;
use yii\helpers\Url;

class GoodsCategoryController extends \yii\web\Controller {
    public function actionIndex(){
        $model=GoodsCategory::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
               if ($model->parent_id){
                   $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                   $model->appendTo($parent);
               }else{
                   $model->makeRoot();
               }
                \Yii::$app->session->setFlash('success','新增商品分类成功');
                return $this->redirect(Url::to(['goods-category/index']));
            }
        }
        $model->parent_id=0;
        return $this->render('alter',['model'=>$model]);
    }

    public function actionUpdate($id){
        $model=GoodsCategory::findOne($id);
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->appendTo($parent);
                }else{
                    if ($model->getOldAttribute('parent_id')){
                        $model->makeRoot();
                    }
                    else{
                        $model->save();
                    }
                }
                \Yii::$app->session->setFlash('success','修改商品分类成功');
                return $this->redirect(Url::to(['goods-category/index']));
            }
        }
        return $this->render('alter',['model'=>$model]);
    }

    public function actionDelete($id){
        $row=GoodsCategory::findOne(['id'=>$id]);
        if ($row){
            if ($row->lft==$row->rgt-1){
                $row->delete();
                echo Json::encode(['status'=>$id]);
            }
            else{
                echo Json::encode(['status'=>'该分类存在子类,不能删除']);
            }
        }
        else{
            echo Json::encode(['status'=>0]);
        }
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except' => ['index','logout','upload','captcha','ueditor'],
            ],
        ];
    }
}