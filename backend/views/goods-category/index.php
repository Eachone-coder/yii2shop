<style>
    tr th,td{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row):?>
        <tr data-id="<?php echo $row->id?>" id="category<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['goods-category/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="3">
            <?php echo \yii\bootstrap\Html::a('新增分类',\yii\helpers\Url::to(['goods-category/add']),['class'=>'btn btn-info btn-lg'])?>
        </td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['goods-category/delete']).'?id=';
$js=<<<JS
    $('.table').on('click','.btn-danger',function() {
        if (confirm('是否确定删除?')){
            var id=$(this).closest('tr').attr('data-id');
            $.getJSON('$html'+id,function(data) {
                if (data.status==id){
                    var name='#category'+id;
                    $(name).fadeOut();
                    alert('删除成功!')
                }else if (data.status==0){
                    alert('删除失败!')
                }else{
                    alert(data.status);
                }
            })
        }
    })
JS;
$this->registerJs($js);