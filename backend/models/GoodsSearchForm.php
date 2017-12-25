<?php
namespace backend\models;

class GoodsSearchForm extends Goods{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    public function rules()
    {
        return [
            [['name','sn','minPrice','maxPrice'],'safe'],
        ];
    }
}