<?php
$form = \yii\bootstrap\ActiveForm::begin();
    echo $form->field($model, 'name')->textInput([
        'placeholder' => '品牌名字长度为2~50之间'
    ]);
    echo $form->field($model, 'intro')->textarea(['rows' => 3, 'placeholder' => '请填写简介']);
    echo $form->field($model,'logo')->hiddenInput(['id'=>'logo']);
    echo '<img src="'.$img.'"  id="oldImg"/>';
    //>>1.先引入css和js
    /**
     * @var $this \yii\web\View
     */
    $this->registerCssFile('@web/webuploader/webuploader.css');
    $this->registerJsFile('@web/webuploader/webuploader.js', ['depends' => \yii\web\JqueryAsset::className()]);
    echo '
    <!--dom结构部分-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
    ';

    $html = \yii\helpers\Url::to(['brand/upload']);
    $js = <<<JS
        // 初始化Web Uploader
        var uploader = WebUploader.create({
    
        // 选完文件后，是否自动上传。
        auto: true,
    
        // swf文件路径
        swf: '/webuploader/Uploader.swf',
    
        // 文件接收服务端。
        server: "{$html}",
    
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',
    
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
        }
    });

        //预览
        // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        $('#oldImg').remove();
        var li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                '</div>'
                ),
            img = li.find('img');
    
    
        //list为容器jQuery实例
            $('#fileList').append( li );
    
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                img.replaceWith('<span>不能预览</span>');
                return;
            }
    
            img.attr( 'src', src );
        }, thumbnailWidth=100, thumbnailHeight=100 );
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ,response) {
        $( '#'+file.id ).addClass('upload-state-done');
        $('#logo').val(response.url);
    });

JS;
    //
    $this->registerJs($js);
    echo $form->field($model, 'sort')->textInput(['type' => 'number']);
    echo $form->field($model, 'status')->inline()->radioList(['0' => '隐藏', '1' => '正常']);
    echo \yii\bootstrap\Html::submitButton('保存', ['class' => 'btn btn-info btn-lg']);
\yii\bootstrap\ActiveForm::end();