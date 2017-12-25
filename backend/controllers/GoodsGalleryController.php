<?php
namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\web\Controller;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use yii\helpers\Json;
class GoodsGalleryController extends Controller{
    public $enableCsrfValidation=false;
    public function actionAdd(){
        $model=new GoodsGallery();
        $request=\Yii::$app->request;
        $model->goods_id=$request->post()['goods_id'];
        $model->path=$request->post()['path'];
        $result=$model->save();
        $id=\Yii::$app->db->getLastInsertID();
        if ($result){
            return Json::encode(['status'=>$id]);
        }
        else{
            return Json::encode(['status'=>0]);
        }
    }

    public function actionDelete($id){
        $row=GoodsGallery::findOne(['id'=>$id]);
        if ($row){
            $row->delete();
            echo json_encode(['status'=>$id]);
        }
        else{
            echo json_encode(['status'=>0]);
        }
    }
    public function actionUpload(){
        $model=UploadedFile::getInstanceByName('file');
        //如果上传则移动
        if ($model){
            $dirName='Upload/GoodsGallery/'.date('Ymd').'/';
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
}