<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'intro')->textarea(['rows'=>5]);
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'status')->inline()->radioList(['-1'=>'删除','0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('新增文章分类',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();