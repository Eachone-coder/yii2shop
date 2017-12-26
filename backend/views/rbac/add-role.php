<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'description')->textInput();
    echo $form->field($model,'permissions')->inline()->checkboxList(\yii\helpers\ArrayHelper::map($list,'name','description'));
    echo \yii\helpers\Html::submitButton('保存',['class'=>'btn btn-primary btn-lg']);
\yii\bootstrap\ActiveForm::end();