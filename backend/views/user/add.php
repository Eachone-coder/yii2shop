<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'username')->textInput();
    echo $form->field($model,'password_hash')->passwordInput();
    echo $form->field($model,'password')->passwordInput();
    echo $form->field($model,'email')->textInput();
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();