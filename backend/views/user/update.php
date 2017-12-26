<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'oldPassword')->passwordInput(['placeholder'=>'不填写旧密码,只修改基本信息']);
echo $form->field($model,'newPassword')->passwordInput(['placeholder'=>'需要填写旧密码']);
echo $form->field($model,'rePassword')->passwordInput(['placeholder'=>'和新密码一致']);
echo $form->field($model,'email')->textInput();
echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();