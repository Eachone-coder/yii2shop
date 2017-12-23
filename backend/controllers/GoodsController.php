<?php
namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class GoodsController extends Controller{
    public $enableCsrfValidation=false;
    public function actionIndex(){
        $query=Goods::find()->where(['status'=>1]);
        //分页
        $pager=new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 5,
        ]);
        $rows=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Goods();
        $introModel=new GoodsIntro();
        $brands=Brand::find()->select(['id','name'])->all();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $introModel->load($request->post());
            $goodsDayCount=GoodsDayCount::findOne(['day'=>$model->date]);
            if ($model->validate()){
                if ($goodsDayCount==null){
                    $goodsDayCount=new GoodsDayCount();
                    $goodsDayCount->day=$model->date;
                    $goodsDayCount->count=1;
                }
               else{
                   $goodsDayCount->count+=1;
               }
                //货号新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
                //查询添加数
                $model->sn=date('Ymd').str_pad($goodsDayCount->count,5,0,0);
                $model->create_time=strtotime($model->date);
                $model->save();
                $introModel->save();
                $goodsDayCount->save();
                \Yii::$app->session->setFlash('success','新增成功');
                return $this->redirect(Url::to(['goods/index']));
            }
            else{
                var_dump($model->getErrors());die;
            }
        }

        return $this->render('alter',['model'=>$model,'introModel'=>$introModel,'brands'=>$brands]);
    }
    public function actionUpdate($id){
        $model=Goods::findOne($id);
        $introModel=GoodsIntro::findOne($id);
        $brands=Brand::find()->select(['id','name'])->all();
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            $introModel->load($request->post());
            if ($model->validate()){
                $model->create_time=strtotime($model->date);
                $model->save();
                $introModel->save();
                \Yii::$app->session->setFlash('success','新增成功');
                return $this->redirect(Url::to(['goods/index']));
            }
            else{
                var_dump($model->getErrors());die;
            }
        }

        return $this->render('alter',['model'=>$model,'introModel'=>$introModel,'brands'=>$brands]);
    }
    public function actionDelete($id){
        $row=Goods::findOne(['id'=>$id]);
        if ($row){
            $row->status=0;
            $row->save();
            echo Json::encode(['status'=>1]);
        }
        else{
            echo Json::encode(['status'=>0]);
        }
    }

    /**
     * @throws \Exception
     */
    public function actionUpload(){
        $model=UploadedFile::getInstanceByName('file');
        //如果上传则移动
        if ($model){
            $dirName='Upload/Goods/'.date('Ymd').'/';
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

    public function actionGallery($id){
        $rows=GoodsGallery::findAll(['goods_id'=>$id]);
        return $this->render('gallery',['rows'=>$rows,'goods_id'=>$id]);
    }

    public function actions()
    {
        return [
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
}