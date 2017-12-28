<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'username')->textInput();
    echo $form->field($model,'password_hash')->passwordInput();
    echo $form->field($model,'password')->passwordInput();
    echo $form->field($model,'email')->textInput();
    echo $form->field($model,'status')->inline()->radioList(['禁用','启用']);
    echo $form->field($model,'roles')->inline()->checkboxList(\yii\helpers\ArrayHelper::map($roles,'name','description'));
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();