<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['parent_id','validPid']
        ];
    }

    public function validPid(){
        $parent=GoodsCategory::findOne(['id'=>$this->parent_id]);
        if ($parent!=null){
            if ($this->parent_id==$this->id){
                $this->addError('parent_id','不能选择自己');
            }
            if ($parent->isChildOf($this)){
                $this->addError('parent_id','不能选择自己的子孙分类');
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上级分类id',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public static function getNodes(){
        $rows=self::find()->select('*')->asArray()->all();
        array_push($rows,['name'=>'顶级分类','id'=>0,'parent_id'=>0]);
        return Json::encode($rows);
    }

}
