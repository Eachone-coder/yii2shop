<style>
    tr th,td{
        text-align: center;
    }
    #head{
        text-align: center;
        font-size: 30px;
    }
    .btn-warning{
        position: relative;
        top: -6px;
    }
</style>
<h1 id="head">文章列表</h1>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($rows as $row):?>
        <tr id="article<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo substr($row->intro,'0','51')?>...</td>
            <td><?php echo $row->category->name?></td>
            <td><?php echo $row->sort?></td>
            <td><?php echo $row->status==-1?'删除':'正常'?></td>
            <td><?php echo date('Y-m-d',$row->create_time)?></td>
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
]);
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['article/del']).'?id=';
$editHtml=\yii\helpers\Url::to(['article/edit']).'?id=';
$js=<<<JS
    $('.table').on('click','.btn-danger',function() {
               if (confirm('确定删除?')){
            var id=$(this).attr('id');
            $.getJSON("$html"+id,function (data) {
                if (data['status']>0){
                    var name='#article'+id;
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

    $('table').on('click','.btn-primary',function() {
                   if (confirm('确定还原为隐藏?')){
                var id=$(this).attr('id');
                $.getJSON("$editHtml"+id,function (data) {
                    if (data['status']>0){
                        var name='#article'+id;
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