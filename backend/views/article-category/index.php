<style>
    tr th,td{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr id="category<?php echo $row->id?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td><?php echo $row->sort?></td>
            <td><?php echo $row->status?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['article-category/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm','id'=>$row->id])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="5">
            <?=\yii\bootstrap\Html::a('增加',\yii\helpers\Url::to(['article-category/add']),['class'=>'btn btn-info btn-lg'])?>
        </td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination' => $pager,'hideOnSinglePage' => false]);
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['article-category/delete']).'?id=';
$js=<<< JS
    $('.btn-danger').click(function(data) {
       if (confirm('确定删除')){
           var id=$(this).attr('id');
       $.getJSON("$html"+id,function(data) {
            if (data['status']>0){
                var name='#category'+id;
                $(name).remove();
                alert('删除成功');
            }
            else{
                alert('删除失败'+date);
            }
       });
       }
    });
JS;
$this->registerJs($js);