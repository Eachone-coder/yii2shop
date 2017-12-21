<style>
    tr td,th{
        text-align: center;
    }
</style>
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
            <td><?php echo ($row->status==0)?'隐藏':'正常'?></td>
            <td><?php echo date('Y-m-d',$row->create_time)?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('查看<span class="glyphicon glyphicon-eye-open"></span>',\yii\helpers\Url::to(['article/show','id'=>$row->id]),['class'=>'btn btn-success btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('修改<span class="glyphicon glyphicon-pencil"></span>',\yii\helpers\Url::to(['article/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\helpers\Html::submitButton('删除<span class="glyphicon glyphicon-trash"></span>',['class'=>'btn btn-danger btn-sm','id'=>$row->id])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7"><?php echo \yii\bootstrap\Html::a('新增文章',\yii\helpers\Url::to(['article/add']),['class'=>'btn btn-info btn-lg'])?></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination' => $pager,
]);
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['article/delete']).'?id=';
$js=<<<JS
    $('.btn-danger').on('click',function() {
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
JS;
$this->registerJs($js);