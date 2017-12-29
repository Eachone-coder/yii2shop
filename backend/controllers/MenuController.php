<?php
namespace backend\controllers;
use backend\filter\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\rbac\Permission;
use yii\web\Controller;

class MenuController extends Controller{
    /**
     * @return string
     */
    public function actionIndex(){
        $query=Menu::find();
        //分页
        $pager=new Pagination([
            'pageSize' => 7,
            'totalCount' => $query->count(),
        ]);
        $rows=[];
        $arrays=$query->all();

        foreach ($arrays as $val){
            if ($val->parent_id==0){
                $parents[]=$val;
            }
        }

        foreach ($parents as $parent){
            $rows[]=$parent;
            foreach ($arrays as $value){
                if ($value->parent_id==$parent->id){
                    $rows[]=$value;
                }
            }
        }

        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model=new Menu();
        $request=\Yii::$app->request;
        $menus=Menu::find()->where(['parent_id'=>0])->asArray()->all();
        array_unshift($menus,['id'=>'0','label'=>'顶级菜单']);
        array_unshift($menus,['id'=>'','label'=>'=请选择菜单=']);
        $authManager=\Yii::$app->authManager;
        $urls=$authManager->getPermissions();
        array_unshift($urls,['name'=>'','name'=>'=请选择路由=']);
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

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        $menus=Menu::find()->where(['parent_id'=>0])->asArray()->all();
        array_unshift($menus,['id'=>'0','name'=>'顶级菜单']);
        array_unshift($menus,['id'=>'','name'=>'请选择菜单']);
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
            ['val'=>'article/delete','name'=>'article/delete'],
            ['val'=>'user/add','name'=>'user/add'],
            ['val'=>'user/update','name'=>'user/update'],
            ['val'=>'user/delete','name'=>'user/delete'],
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

    /**
     * @param $id
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id){
        $row=Menu::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            echo Json::encode(['status'=>$id]);
        }else{
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