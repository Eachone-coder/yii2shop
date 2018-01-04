<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $address
 * @property string $tel
 * @property integer $is_default
 * @property integer $created_at
 * @property integer $updated_at
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'name', 'cmbProvince', 'cmbCity', 'cmbArea', 'address', 'tel',], 'required'],
            [['member_id', 'is_default', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['cmbProvince'], 'string', 'max' => 10],
            [['cmbCity'], 'string', 'max' => 20],
            [['cmbArea'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'cmbProvince' => '所在省',
            'cmbCity' => '所在市',
            'cmbArea' => '所在县',
            'address' => '详情地址',
            'tel' => '电话',
            'is_default' => '默认地址',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }
}
