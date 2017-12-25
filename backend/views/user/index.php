<style>
    tr td,th{
        text-align: center;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th>用户名</th>
        <th>邮箱</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr data-id="<?php echo $row->id?>" id="user<?php echo $row->id?>">
            <td><?php echo $row->username?></td>
            <td><?php echo $row->email?></td>
            <td><?php echo date('Y-m-d',$row->last_login_time)?></td>
            <td><?php echo $row->last_login_ip?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['user/update','id'=>$row->id]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6"><?php echo \yii\bootstrap\Html::a('添加',\yii\helpers\Url::to(['user/add']),['class'=>'btn btn-info btn-lg'])?></td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$html=\yii\helpers\Url::to(['user/delete']).'?id=';
$js=<<<JS
    $('.container').on('click','.btn-danger',function() {
        if (confirm('请确定是否删除')){
            var id=$(this).closest('tr').attr('data-id');
            $.getJSON('$html'+id,function(data) {
               if (data.status==id){
                   var name='#user'+id;
                   $(name).fadeOut();
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