<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'username')->textInput();
    echo $form->field($model,'password')->passwordInput();
    echo $form->field($model,'varCode')->widget(\yii\captcha\Captcha::className(),[
        'captchaAction' => 'login/captcha',
        'template' =>   '<div class="row"><div class="col-md-4">{input}</div> <div class="col-md-4">{image}</div></div>',
    ]);
    echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();