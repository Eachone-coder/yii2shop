<style>
    tr td,th{
        text-align: center;
    }
</style>
<h1>商品列表</h1>
<?php
$form=\yii\bootstrap\ActiveForm::begin(['method' => 'get','options' => ['class'=>'form-inline','role'=>'form'],]);
    echo $form->field($goods,'name')->textInput(['placeholder'=>'商品名称',])->label('');
    echo $form->field($goods,'sn')->textInput(['type'=>'number','placeholder'=>'商品货号'])->label('');
    echo $form->field($goods,'minPrice')->textInput(['type'=>'number','placeholder'=>'最小价格'])->label('');
    echo $form->field($goods,'maxPrice')->textInput(['type'=>'number','placeholder'=>'最大价格'])->label('');
    echo \yii\bootstrap\Html::submitButton('<span class="glyphicon glyphicon-search"></span>搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>

<table class="table table-border">
    <tr>
        <th>名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>浏览次数</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr id="goods<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->sn?></td>
            <td><img src="<?php echo $row->logo?>" alt="" class="img-thumbnail" width="70px"></td>
            <td><?php echo $row->shop_price?></td>
            <td><?php echo $row->stock?></td>
            <td><?php echo ($row->is_on_sale)?'在售':'下架'?></td>
            <td><?php echo $row->view_times?>次</td>
            <td>
                <?php echo \yii\bootstrap\Html::a('相册',\yii\helpers\Url::to(['goods/gallery','id'=>$row->id]),['class'=>'btn btn-info btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('预览',\yii\helpers\Url::to(['goods/show','id'=>$row->id]),['class'=>'btn btn-success btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['goods/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm','id'=>$row->id])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9">
            <?php echo \yii\bootstrap\Html::a('新增',\yii\helpers\Url::to(['goods/add']),['class'=>'btn btn-info btn-lg'])?>
        </td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
echo \yii\widgets\LinkPager::widget(['pagination' => $pager]);
$html=\yii\helpers\Url::to(['goods/delete']).'?id=';
$js=<<<JS
    $('.btn-danger').on('click',function() {
        if (confirm('是否确定删除')){
            var id =$(this).attr('id');
        $.getJSON('$html'+id,function(data) {
            console.debug(data.status);
            if (data.status!=0){
                var name='#goods'+id;
                $(name).fadeOut();
                alert('删除成功');
            }
            else{
                alert('删除失败');
            }
        })
        }
    });
JS;
$this->registerJs($js);