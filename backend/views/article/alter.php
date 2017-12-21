<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'intro')->textarea(['rows'=>4]);
    echo $form->field($model,'details')->widget(\yii\redactor\widgets\Redactor::className(),[
        'clientOptions' => [
            'imageManagerJson' => ['/redactor/upload/image-json'],
            'imageUpload' => ['/redactor/upload/image'],
            'fileUpload' => ['/redactor/upload/file'],
            'lang' => 'zh_cn',
            'plugins' => ['clips', 'fontcolor','imagemanager']
        ],
    ]);
    echo $form->field($model,'sort')->textInput();
    echo $form->field($model,'status')->inline()->radioList(['-1'=>'删除','0'=>'隐藏','1'=>'正常']);
    echo $form->field($model,'date_time')->widget(\kartik\date\DatePicker::className(),[
        'options' => [
            'value' => date('Y-m-d',time()),
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            ]
    ]);
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();