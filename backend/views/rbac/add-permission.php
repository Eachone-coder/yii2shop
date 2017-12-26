<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput(['placeholder'=>'填写示例:  user/add']);
    echo $form->field($model,'description')->textInput();
    echo \yii\helpers\Html::submitButton('保存',['class'=>'btn btn-primary btn-lg']);
\yii\bootstrap\ActiveForm::end();