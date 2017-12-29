<?php

namespace backend\controllers;

use backend\filter\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $model=Brand::find()->where(['status' => [0,1]]);

        //分页
        $pager=new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => 7,
        ]);

        $rows=$model->addOrderBy('sort')->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost){
            //绑定数据
            $model->load($request->post());
            if ($model->validate()){
                //保存
                $model->save();
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
        $img='';
        return $this->render('alter',['model'=>$model,'img'=>$img]);
    }
    public function actionUpdate($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if ($request->isPost){
            //post方式
            //绑定数据
            $model->load($request->post());
            if ($model->validate()){
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
        $img=$model->logo;
        return $this->render('update',['model'=>$model,'img'=>$img]);
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

    public function actionDel($id){
        $row=Brand::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>$row->getErrors()]);
        }
    }

    public function actionEdit($id){
        $row=Brand::findOne(['id'=>$id]);
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
     * @throws \Exception
     */
    public function actionUpload(){
        $model=UploadedFile::getInstanceByName('file');
        //如果上传则移动
        if ($model){
            $dirName='Upload/Brand/'.date('Ymd').'/';
            //创建路径
            if (!is_dir($dirName)){
                mkdir($dirName,0777,true);
            }
            $fileName=uniqid().'.'.$model->extension;
            if ($model->saveAs(\Yii::getAlias('@webroot').'/'.$dirName.$fileName)){
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="rWoVtEsy7XxYkUt0ZputvXtAunPTQxJiacYhb5nT";
                $secretKey = "iobjvc7w3THiggBPgvlXQmyXU1iPv3Kselam5Tlw";
                $bucket = "shop";

                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);

                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').'/'.$dirName.$fileName;

                // 上传到七牛后保存的文件名
                $key = '/'.$dirName.$fileName;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                //echo "\n====> putFile result: \n";
                if ($err !== null) {
//                    var_dump($err);
                    echo Json::encode(['status'=>0]);
                }
                else {
                    echo Json::encode(['url'=>'http://p1aurjprl.bkt.clouddn.com//'.$dirName.$fileName]);
//
                }
            }else{
                echo Json::encode(['status'=>0]);
            }
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
