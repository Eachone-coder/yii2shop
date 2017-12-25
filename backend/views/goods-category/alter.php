<?php
/**
 * @var $this \yii\web\View
 */
//添加css和js
//$this->registerCssFile('@web/zTree/css/demo.css');
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$form=\kartik\form\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'parent_id')->textInput();
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('@web/zTree/js/jquery.ztree.excheck.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('@web/zTree/js/jquery.ztree.exedit.js',['depends'=>\yii\web\JqueryAsset::className()]);

$nodes=\backend\models\GoodsCategory::getNodes();
$js =<<<JS
       
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
		                $('#goodscategory-parent_id').val(treeNode.id);
		            }
	            }
            };
            var zNodes=$nodes;
			var treeObj=$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			treeObj.expandAll(true);
			var node = treeObj.getNodeByParam("id",'$model->parent_id', null);
			treeObj.selectNode(node);
	
JS;
$this->registerJs($js);
echo '
	<div class="zTreeDemoBackground">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
    ';
    echo $form->field($model,'intro')->textInput();
    echo \yii\bootstrap\Html::submitButton('保存',['class'=>'btn btn-info btn-lg']);
\kartik\form\ActiveForm::end();