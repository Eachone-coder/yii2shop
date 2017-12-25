<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'));
    echo $form->field($model,'intro')->textarea(['rows'=>4]);
    echo $form->field($sonModel,'content')->widget(\common\widgets\ueditor\Ueditor::className());
    echo $form->field($model,'sort')->textInput(['type'=>'tel']);
    echo $form->field($model,'status')->inline()->radioList(['0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();