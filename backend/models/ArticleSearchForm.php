<?php
namespace backend\models;

class ArticleSearchForm extends Article{
    public $name;
    public $intro;

    public function rules(){
        return [
            [['name','intro'],'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'',
          'intro'=>'',
        ];
    }
}