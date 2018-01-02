<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;
use yii\helpers\Url;

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
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['parent_id', 'validPid']
        ];
    }

    public function validPid()
    {
        $parent = GoodsCategory::findOne(['id' => $this->parent_id]);
        if ($parent != null) {
            if ($this->parent_id == $this->id) {
                $this->addError('parent_id', '不能选择自己');
            }
            if ($parent->isChildOf($this)) {
                $this->addError('parent_id', '不能选择自己的子孙分类');
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

    public function behaviors()
    {
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

    public static function getNodes()
    {
        $rows = self::find()->select('*')->asArray()->all();
        array_push($rows, ['name' => '顶级分类', 'id' => 0, 'parent_id' => 0]);
        return Json::encode($rows);
    }

    public static function getCategories()
    {
        //redis
        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        $html=$redis->get('category_html');
        if (!$html){
            //==================================
            $firstCategory = \backend\models\GoodsCategory::find()->where(['parent_id' => 0])->all();
            foreach ($firstCategory as $k1 => $first) {
                $html .= '<div class="cat ' . ($k1 ? '' : 'item1') . '">';
                $html .= '<h3><a href="'.Url::to(['goods/goods-category','id'=>$first->id]).'">' . $first->name . '</a><b></b></h3>';
                $html .= '<div class="cat_detail">';
                $secondCategory = \backend\models\GoodsCategory::find()->where(['parent_id' => $first->id])->all();
                foreach ($secondCategory as $k2 => $second) {
                    $html .= '<dl ' . ($k2 ? '' : 'dl_1st') . '>';
                    $html .= '<dt><a href="'.Url::to(['goods/goods-category','id'=>$second->id]).'">' . $second->name . '</a></dt>';
                    $html .= '<dd>';
                    $thridCategory = \backend\models\GoodsCategory::find()->where(['parent_id' => $second->id])->all();
                    foreach ($thridCategory as $thrid) {
                        $html .= '<a href="'.Url::to(['goods/goods-category','id'=>$thrid->id]).'">' . $thrid->name . '</a>';
                    }
                    $html .= '</dd>';
                    $html .= '</dl>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            //==================================
            $redis->set('category_html',$html,24*3600);
        }
        return $html;
    }
}
