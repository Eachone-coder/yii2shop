<style>
    tr td,th{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($rows as $row):?>
        <tr>
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td><?php echo $row->sort?></td>
            <td><?php echo $row->status?></td>
            <td><?php echo $row->create_time?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['article/update']),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\helpers\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6"><?php echo \yii\bootstrap\Html::a('新增文章',\yii\helpers\Url::to(['article/add']),['class'=>'btn btn-info btn-lg'])?></td>
    </tr>
</table>