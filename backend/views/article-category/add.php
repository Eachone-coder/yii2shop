<?php
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput(['placeholder'=>'分类名称字数在2~50之间']);
    echo $form->field($model,'intro')->textarea(['rows'=>5,'placeholder'=>'简介不能为空']);
    echo $form->field($model,'sort')->textInput(['type'=>'tel','placeholder'=>'排序只能是数字']);
    echo $form->field($model,'status')->inline()->radioList(['0'=>'隐藏','1'=>'正常']);
    echo \yii\bootstrap\Html::submitButton('新增文章分类',['class'=>'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();
$js=<<<JS
    $('.container').on('keyup','#articlecategory-name',function() {
            var length=$(this).val().length;
            var html='<p>当前已输入'+length+'个字</p>';
            $('.field-articlecategory-name p').html(html);
        });

    $('.container').on('keyup','#articlecategory-intro',function() {
        var length=$(this).val().length;
        var html='<p>当前已输入'+length+'个字</p>';
        $('.field-articlecategory-intro p').html(html);
    });
JS;
/**
 * @var $this \yii\web\View
 */
$this->registerJs($js);