<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    public $uploadFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','intro','sort','status'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50,'tooLong' => '品牌名称最长为50字','min' => 2,'tooShort' => '品牌名称最短为2字'],
            [['logo'], 'string', 'max' => 255,],
            ['uploadFile','file','extensions'=>['jpg','png','gif'],'maxSize'=>2*1024*1024,'skipOnEmpty'=>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
            'uploadFile' => 'LOGO图片(不上传自动选择默认图片)',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}