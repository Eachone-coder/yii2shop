<?php
namespace backend\controllers;
use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class MenuController extends Controller{
    public function actionIndex(){
        $query=Menu::find();
        //分页
        $pager=new Pagination([
            'pageSize' => 7,
            'totalCount' => $query->count(),
        ]);

        $rows=$query->all();
        return $this->render('index',['rows'=>$rows]);
    }
    public function actionAdd(){
        $model=new Menu();
        $request=\Yii::$app->request;
        $menus=[
            ['parent_id'=>'','name'=>'请选择上级菜单'],
            ['parent_id'=>'1','name'=>'商品管理'],
            ['parent_id'=>'2','name'=>'品牌管理'],
            ['parent_id'=>'3','name'=>'管理员管理'],
            ['parent_id'=>'4','name'=>'用户管理'],
        ];
        $urls=[
            ['val'=>'','name'=>'请选择路由'],
            ['val'=>'goods/add','name'=>'goods/add'],
            ['val'=>'goods/update','name'=>'goods/update'],
            ['val'=>'goods/delete','name'=>'goods/delete'],
            ['val'=>'brand/add','name'=>'brand/add'],
            ['val'=>'brand/update','name'=>'brand/update'],
            ['val'=>'brand/delete','name'=>'brand/delete'],
            ['val'=>'article/add','name'=>'article/add'],
            ['val'=>'article/update','name'=>'article/update'],
            ['val'=>'article/delete','name'=>'article/delete'],
        ];
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(Url::to(['menu/index']));
            }
        }
        return $this->render('add',['model'=>$model,'menus'=>$menus,'urls'=>$urls]);
    }

    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        $menus=[
            ['parent_id'=>'','name'=>'请选择上级菜单'],
            ['parent_id'=>'1','name'=>'商品管理'],
            ['parent_id'=>'2','name'=>'品牌管理'],
            ['parent_id'=>'3','name'=>'管理员管理'],
            ['parent_id'=>'4','name'=>'用户管理'],
        ];
        $urls=[
            ['val'=>'','name'=>'请选择路由'],
            ['val'=>'goods/add','name'=>'goods/add'],
            ['val'=>'goods/update','name'=>'goods/update'],
            ['val'=>'goods/delete','name'=>'goods/delete'],
            ['val'=>'brand/add','name'=>'brand/add'],
            ['val'=>'brand/update','name'=>'brand/update'],
            ['val'=>'brand/delete','name'=>'brand/delete'],
            ['val'=>'article/add','name'=>'article/add'],
            ['val'=>'article/update','name'=>'article/update'],
            ['val'=>'article/delete','name'=>'article/delete'],
        ];
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(Url::to(['menu/index']));
            }
        }
        return $this->render('add',['model'=>$model,'menus'=>$menus,'urls'=>$urls]);
    }

    public function actionDelete($id){
        $row=Menu::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            echo Json::encode(['status'=>$id]);
        }else{
            echo Json::encode(['status'=>0]);
        }
    }
}