<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'logo')->hiddenInput();
//=============================图片上传
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js', ['depends' => \yii\web\JqueryAsset::className()]);
//=============================分类
//注册css js
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//=============================上传图片
    echo <<<HTML
        <!--dom结构部分-->
        <div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>
        </div>
HTML;
//=========================end
//=========================商品分类
echo $form->field($model,'goods_category_id')->hiddenInput();
echo <<<Ztree
    <div>
       <ul id="treeDemo" class="ztree"></ul>
    </div>
Ztree;
$Nodes=\backend\models\GoodsCategory::getNodes();
//=========================end
    //js部分
$html=\yii\helpers\Url::to(['goods/upload']);
$js=<<<JS
        // 初始化Web Uploader
        var uploader = WebUploader.create({
        
            // 选完文件后，是否自动上传。
            auto: true,
        
            // swf文件路径
            swf: '/webuploader/Uploader.swf',
        
            // 文件接收服务端。
            server: '{$html}',
        
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


       // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                    '</div>'
                    ),
                img = li.find('img');
        
        
            // list为容器jQuery实例
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
            uploader.on( 'uploadSuccess', function( file,response ) {
                 $( '#'+file.id ).addClass('upload-state-done');
                 $('#goods-logo').val(response.url);
            });
            
            
            var zTreeObj;
           // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
           var setting = {
               data: {
		        simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
		            }
	            },
	            callback: {
		            onClick: function(event, treeId, treeNode) {
		                $('#goods-goods_category_id').val(treeNode.id);
		            }
	            }
           };
           // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
              var zNodes={$Nodes};
              zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
              zTreeObj.expandAll(true);
JS;
$this->registerJs($js);

    echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brands,'id','name'));
    echo $form->field($model,'market_price')->textInput(['type'=>'number']);
    echo $form->field($model,'shop_price')->textInput(['type'=>'number']);
    echo $form->field($model,'stock')->textInput(['type'=>'number']);
    echo $form->field($model,'is_on_sale')->inline()->radioList(['下架','在售']);
    echo $form->field($model,'status')->inline()->radioList(['回收站','正常']);
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($introModel,'content')->widget(\common\widgets\ueditor\Ueditor::className(),[
        'options' => [
            'initialFrameHeight'=>500
        ],
    ]);
    echo $form->field($model,'date')->widget(\kartik\date\DatePicker::className(),[
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