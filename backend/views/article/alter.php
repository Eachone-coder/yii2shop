<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput(['placeholder'=>'文章标题']);
    echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'));
//    echo $form->field($model,'intro')->textarea(['rows'=>4,'placeholder'=>'文章简介']);
    echo $form->field($model,'intro')->widget(\yii\redactor\widgets\Redactor::className(),[
        'clientOptions' => [
            'imageManagerJson' => ['/redactor/upload/image-json'],
            'imageUpload' => ['/redactor/upload/image'],
            'fileUpload' => ['/redactor/upload/file'],
            'lang' => 'zh_cn',
            'plugins' => ['clips', 'fontcolor','imagemanager']
        ]
    ]);
    echo $form->field($sonModel,'content')->widget(\common\widgets\ueditor\Ueditor::className(),[
        'options' => [
            'initialFrameHeight'=>500
        ],
    ]);
    echo $form->field($model,'sort')->textInput(['type'=>'tel']);
    echo $form->field($model,'status')->inline()->radioList(['0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();