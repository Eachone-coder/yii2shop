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
        <tr>
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['goods-category/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn=danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="3">
            <?php echo \yii\bootstrap\Html::a('新增分类',\yii\helpers\Url::to(['goods-category/add']),['class'=>'btn btn-info btn-lg'])?>
        </td>
    </tr>
</table>