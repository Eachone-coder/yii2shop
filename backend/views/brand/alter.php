<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'intro')->textarea();
    echo $form->field($model,'uploadFile')->fileInput();
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'status')->inline()->radioList(['-1'=>'删除','0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();