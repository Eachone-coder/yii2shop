<style>
    tr th,td{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>地址/路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr data-id="<?php echo $row->id?>" id="menu<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->url?></td>
            <td><?php echo $row->sort?></td>
            <td>
                <?php echo \yii\helpers\Html::a('修改',\yii\helpers\Url::to(['menu/edit','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\helpers\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="4"><?php echo \yii\helpers\Html::a('新增',\yii\helpers\Url::to(['menu/add']),['class'=>'btn btn-info'])?></td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['menu/delete']).'?id=';
$js=<<<JS
    $('table').on('click','.btn-danger',function() {
        if (confirm('确定删除?')){
            var id=$(this).closest('tr').attr('data-id');
            $.getJSON('$html'+id,function(data) {
                console.debug(data.status);
                if (data.status == id){
                    var name='#menu'+id;
                    $(name).remove();
                    alert('删除成功');
                }
                else{
                    alert('删除失败');
                }
            })
        }    
    })
JS;
$this->registerJs($js);