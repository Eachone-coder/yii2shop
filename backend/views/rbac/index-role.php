<style>
    tr td,th{
        text-align: center;
    }
</style>
<table class="table table-bordered display" id="table_id_example">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($rows as $row):?>
        <tr data-name="<?php echo $row->name?>">
            <td><?php echo $row->name?></td>
            <td><?php echo $row->description?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('修改',\yii\helpers\Url::to(['rbac/edit-role','name'=>$row->name]),['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\helpers\Html::submitButton('删除',['class'=>'btn btn-danger btn-sm',])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
    <tr>
        <td colspan="3"><?php echo \yii\helpers\Html::a('添加权限',\yii\helpers\Url::to(['rbac/add-role']),['class'=>'btn btn-info btn-lg'])?></td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
//注册css js
$this->registerCssFile('@web/DataTables/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
$html=\yii\helpers\Url::to(['rbac/del-role']).'?name=';
$js=<<<JS
    <!--第三步：初始化Datatables-->
        $('#table_id_example').DataTable({
        scrollY: 400,
        language: {
            decimal: ",",
            search: "在表格中搜索:",
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
        });
//===============================
        $('.table').on('click','.btn-danger',function() {
            if (confirm('确定删除该角色?')){
                var tr=$(this).closest('tr');
                var id=tr.attr('data-name');
                $.getJSON('$html'+id,function(data) {
                    if (data.status==1){
                        tr.fadeOut();
                        alert('删除成功');
                    } else{
                        alert('删除失败')
                    }
                })
            }
        })
JS;
$this->registerJs($js);