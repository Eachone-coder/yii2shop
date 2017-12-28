<?php
$form=\kartik\form\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($menus,'id','name'));
    echo $form->field($model,'url')->dropDownList(\yii\helpers\ArrayHelper::map($urls,'val','name'));
    echo $form->field($model,'sort')->textInput(['type'=>'tel']);
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
    \kartik\form\ActiveForm::end();