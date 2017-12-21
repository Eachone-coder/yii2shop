<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput([
        'placeholder'=>'品牌名字长度为2~50之间'
    ]);
    echo $form->field($model,'intro')->textarea(['rows'=>3,'placeholder'=>'请填写简介']);
    echo $form->field($model,'uploadFile')->widget(\kartik\file\FileInput::className(),[
        'options' => [
            'multiple' => true
        ],
    ]);
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'status')->inline()->radioList(['-1'=>'删除','0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();