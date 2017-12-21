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
            [['name','intro','sort','status','logo'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50,'tooLong' => '品牌名称最长为50字','min' => 2,'tooShort' => '品牌名称最短为2字'],
            [['logo'], 'string', 'max' => 255,],
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
            'logo' => 'LOGO图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
