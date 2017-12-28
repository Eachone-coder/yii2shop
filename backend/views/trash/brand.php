<style>
    tr th,td{
        text-align: center;
    }
    #head{
        text-align: center;
        font-size: 30px;
    }
</style>
<h1 id="head">商品品牌列表</h1>
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
                <?php echo \yii\helpers\Html::submitButton('隐藏',['class'=>'btn btn-primary btn-sm','id'=>$row->id])?>
                <?php echo \yii\helpers\Html::submitButton('彻底删除',['class'=>'btn btn-danger btn-sm','id'=>$row->id])?>
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
$html=\yii\helpers\Url::to(['brand/del']).'?id=';
$editHtml=\yii\helpers\Url::to(['brand/edit']).'?id=';
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

    $("table").on('click','.btn-primary',function(){
            if (confirm('确定还原?')){
                var id=$(this).attr('id');
                $.getJSON("$editHtml"+id,function (data) {
                    if (data['status']>0){
                        var name='#brand'+id;
                        $(name).remove();
                        alert('还原成功')
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