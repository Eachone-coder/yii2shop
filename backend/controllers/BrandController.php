<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=Brand::find()->where(['status' => [0,1]]);

        //分页
        $pager=new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => 5,
        ]);

        $rows=$model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost){
            //绑定数据
            $model->load($request->post());
            //处理上传图片
            $model->uploadFile=UploadedFile::getInstance($model,'uploadFile');
            //默认
            if ($model->validate()){
                //如果上传则移动
                if ($model->uploadFile){
                    $dirName='Upload/Brand/'.date('Ymd').'/';
                    //创建路径
                    if (!is_dir($dirName)){
                        mkdir($dirName,0777,true);
                    }
                    $fileName=uniqid().'.'.$model->uploadFile->extension;
                    if ($model->uploadFile->saveAs(\Yii::getAlias('@webroot').'/'.$dirName.$fileName)){
                        $model->logo='/'.$dirName.$fileName;
                    }
                }
                //默认
                $model->logo='/Upload/Brand/20171220/5a3a2326df1d6.jpg';
                //保存
                $model->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','新增品牌成功');
                //重定向
                return $this->redirect(Url::to(['brand/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        //默认选中隐藏
        $model->status=0;
        return $this->render('alter',['model'=>$model]);
    }
    public function actionUpdate($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            //post方式
            //绑定数据
            $model->load($request->post());
            //处理上传图片
            $model->uploadFile=UploadedFile::getInstance($model,'uploadFile');
            //默认
            if ($model->validate()){
                //如果上传则移动
                if ($model->uploadFile){
                    $dirName='/Upload/Brand/'.date('Ymd').'/';
                    //创建路径
                    if (!is_dir($dirName)){
                        mkdir($dirName,0777,true);
                    }
                    $fileName=uniqid().'.'.$model->uploadFile->extension;
                    if ($model->uploadFile->saveAs(\Yii::getAlias('@webroot').$dirName.$fileName)){
                        $model->logo=$dirName.$fileName;
                    }
                }
                //保存
                $model->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改品牌成功');
                //重定向
                return $this->redirect(Url::to(['brand/index']));
            }
            else{
                var_dump($model->getErrors());
            }
        }
        //get方式
        return $this->render('alter',['model'=>$model]);
    }

    public function actionDelete($id){
        $row=Brand::findOne(['id'=>$id]);
        if ($row){
            $row->status=-1;
            $row->save();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

}
