<?=\yii\bootstrap\Html::a('增加',\yii\helpers\Url::to(['brand/add']),['class'=>'btn btn-info btn-lg'])?>
<style>
    tr th,td{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr id="brand<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td><img src="<?php echo $row->logo?>" alt="品牌图片" class="img-thumbnail" width="70px"></td>
            <td><?php echo $row->sort?></td>
            <td><?php echo ($row->status==-1)?'删除':($row->status==0?'隐藏':'正常')?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['brand/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm','id'=>$row->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget([
        'pagination' => $pager,
        'hideOnSinglePage'=>false,
]);
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['brand/delete']).'?id=';
$js= <<<JS
 $("table").on('click','.btn-danger',function(){
        if (confirm('确定删除?')){
            var id=$(this).attr('id');
            $.getJSON("$html"+id,function (data) {
                if (data['status']>0){
                    var name='#brand'+id;
                    $(name).remove();
                    alert('删除成功')
                }
                else{
                    alert(data['status'])
                }
            })
        }
        return false;
    });
JS;
$this->registerJs($js);
?>