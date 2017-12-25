<?php
/**
 * @var $this \yii\web\View
 */
//引入css js
//=============================图片上传
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js', ['depends' => \yii\web\JqueryAsset::className()]);

echo <<<HTML
     <!--dom结构部分-->
        <div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>
        </div>
HTML;
echo '<table class="table">';
foreach ($rows as $row):
    echo '<tr  id="gallery'.$row->id.'" data-id="'.$row->id.'"><td><img src="'.$row->path.'"></td><td>
'.\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm']).'
</td></tr>';
endforeach;
echo '</table>';
$html=\yii\helpers\Url::to(['goods-gallery/upload']);   //上传地址
$alterHtml=\yii\helpers\Url::to(['goods-gallery/add']); //添加地址
$delHtml=\yii\helpers\Url::to(['goods-gallery/delete']).'?id='; //删除地址

$js=<<<JS
// 初始化Web Uploader
            var uploader = WebUploader.create({
            
                // 选完文件后，是否自动上传。
                auto: true,
            
                // swf文件路径
                swf: '/webuploader/Uploader.swf',
            
                // 文件接收服务端。
                server: '$html',
            
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker',
            
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
                }
            });
            
            // 文件上传过程中创建进度条实时显示。
            uploader.on( 'uploadProgress', function( file, percentage ) {
                var li = $( '#'+file.id ),
                    percent = li.find('.progress span');
            
                // 避免重复创建
                if ( !percent.length ) {
                    percent = $('<p class="progress"><span></span></p>')
                            .appendTo( li )
                            .find('span');
                }
            
                percent.css( 'width', percentage * 100 + '%' );
            });

// 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file,response ) {
                 $( '#'+file.id ).addClass('upload-state-done');
                 $.post('$alterHtml',{"goods_id":$goods_id,"path":response.url},function(data) {
                     console.debug(data);
                    if (data.status!=0){
                        //追加html
                        var str='<tr id="gallery'+data.status+'" data-id="'+data.status+'"><td><img src="'+response.url+'"></td><td><button type="submit" class="btn btn-danger btn-sm">删除</button></td></tr>';
                        $('.table'). append(str);
                    }
                    else {
                        alert('添加失败');
                    }
                 },'json');
            });

// 文件上传失败，显示上传出错。
uploader.on( 'uploadError', function( file ) {
    var li = $( '#'+file.id ),
        error = li.find('div.error');

    // 避免重复创建
    if ( !error.length ) {
        error = $('<div class="error"></div>').appendTo( li );
    }

    error.text('上传失败');
});

$('.table').on('click','.btn-danger',function() {
    if (confirm('此操作会强制删除图片!是否确定删除?')){
            var id=$(this).closest('tr').attr('data-id');
            $.getJSON('$delHtml'+id,function(data) {
            if (data.status>0){
                var tab='#gallery'+(data.status);
                $(tab).fadeOut();
                alert('删除成功');
            }
            else{
                alert('删除失败');
            }
        });
    }
});   
JS;
$this->registerJs($js);