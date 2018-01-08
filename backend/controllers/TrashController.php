<?php
namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;

class TrashController extends Controller{
    public function actionArticle()
    {
        $query=Article::find()->where(['status'=>'-1']);
        $pager=new Pagination([
            'pageSize' => 6,
            'totalCount' => $query->count(),
        ]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('article',['rows'=>$rows,'pager'=>$pager]);
    }

    public function actionBrand()
    {
        $query=Brand::find()->where(['status'=>'-1']);
        $pager=new Pagination([
            'pageSize' => 6,
            'totalCount' => $query->count(),
        ]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('brand',['rows'=>$rows,'pager'=>$pager]);
    }

    public function actionArticleCategory()
    {
        $query=ArticleCategory::find()->where(['status'=>'-1']);
        $pager=new Pagination([
            'pageSize' => 6,
            'totalCount' => $query->count(),
        ]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('article-category',['rows'=>$rows,'pager'=>$pager]);
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