<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use backend\models\ArticleSearchForm;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class ArticleController extends Controller{
    /**
     * @return string
     */
    public function actionIndex(){
        $query=Article::find()->where(['status'=>[0,1]]);
        $search=new ArticleSearchForm();
        $search->load(\Yii::$app->request->get());
        if (count($search)){
            if ($search->name){
                $query->andWhere(['like','name',$search->name]);
            }
            if ($search->intro){
                $query->andWhere(['like','intro',$search->intro]);
            }
        }
        $pager=new Pagination([
            'pageSize' => 7,
            'totalCount' => $query->count(),
        ]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager,'searchForm'=>$search]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model=new Article();
        $sonModel=new ArticleDetail();
        $request=\Yii::$app->request;
        $category=ArticleCategory::find()->where(['status'=>[0,1]])->select(['id','name'])->asArray()->all();
        array_unshift($category,['id'=>'','name'=>'请选择']);
        if ($request->isPost){
            $model->load($request->post());
            $sonModel->load($request->post());
            if ($model->validate() && $sonModel->validate()){
                //处理文章文章详情
                   $sonModel->save();
                   //处理文章
                   $model->save();

                   \Yii::$app->session->setFlash('success','新增文章成功');
                    return $this->redirect(Url::to(['article/index']));
            }else{
                var_dump($model->getErrors());
            }
        }
        $model->status=0;
        return $this->render('alter',['model'=>$model,'sonModel'=>$sonModel,'category'=>$category]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id){
        $model=Article::findOne($id);
        $sonModel=ArticleDetail::findOne($id);
        $request=\Yii::$app->request;
        $category=ArticleCategory::find()->where(['status'=>[0,1]])->select(['id','name'])->asArray()->all();
        array_unshift($category,['id'=>'','name'=>'请选择']);
        if ($request->isPost){
            $model->load($request->post());
            $sonModel->load($request->post());
            if ($model->validate() && $sonModel->validate()){
                //处理文章文章详情
                $sonModel->save();
                //处理文章
                $model->save();

                \Yii::$app->session->setFlash('success','修改文章成功');
                return $this->redirect(Url::to(['article/index']));
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('alter',['model'=>$model,'sonModel'=>$sonModel,'category'=>$category]);
    }

    /**
     * @param $id
     */
    public function actionDelete($id){
        $row=Article::findOne(['id'=>$id]);
        if ($row){
            $row->status=-1;
            $row->save();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

    /**
     * @param $id
     */
    public function actionDel($id){
        $row=Article::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

    public function actionEdit($id){
        $row=Article::findOne(['id'=>$id]);
        if ($row){
            $row->status=0;
            $row->save();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionShow($id){
        $model=Article::findOne($id);
        $sonModel=ArticleDetail::findOne($id);
        $category=ArticleCategory::find()->where(['status'=>[0,1]])->all();
        return $this->render('show',['model'=>$model,'sonModel'=>$sonModel,'category'=>$category]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    'imageUrlPrefix' => "http://www.admin.shop.com", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
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