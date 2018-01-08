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
                <?php echo \yii\helpers\Html::submitButton('彻底删除',['class'=>'btn btn-danger btn-sm','d-id'=>$row->id])?>
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
\light\widgets\SweetSubmitAsset::register($this);
$this->registerJsFile('@web/layer/layer.js',['depends'=>\yii\web\JqueryAsset::className()]);
$html=\yii\helpers\Url::to(['brand/del']).'?id=';
$editHtml=\yii\helpers\Url::to(['brand/edit']).'?id=';
$js= <<<JS
 $("table").on('click','.btn-danger',function(){
            layer.confirm('确定删除?',{icon: 3, title:'提示'},function(index) {
              var id=$('.btn-danger').attr('d-id');
            $.getJSON("$html"+id,function (data) {
                if (data['status']>0){
                    var name='#brand'+id;
                    $(name).remove();
                    layer.msg('删除成功')
                }
                else{
                    layer.msg(data['status'])
                }
            });
            layer.close(index);
            })
    });


        $('.btn-primary').on('click',function() {
            var id=$(this).attr('id');
                swal({ 
                  title: "确定还原",              //弹窗的标题
                  text: "点击OK将还原该记录",      //弹窗的描述
                  type: "info",              //弹窗的类型
                  showCancelButton: true,       //如果设置为true，“取消”按钮将会显示，用户点击取消按钮会关闭弹窗
                //showConfirmButton	true	    //如果设置为false，“确认”按钮将会隐藏。
                //confirmButtonText	"OK"	    //使用该参数来修改“确认”按钮的显示文本。
                //confirmButtonColor	"#AEDEF4"	//使用该参数来修改“确认”按钮的背景颜色（必须是十六进制值）。
                  confirmButtonColor: '#4cd964',
                  closeOnConfirm: false,        //设置为false，用户点击“确认”按钮后，弹窗会继续保持打开状态。如果点击“确认”按钮后需要打开另一个SweetAlert弹窗，这是非常有用的
                //closeOnCancel     这和closeOnConfirm的功能相似，只不过这个是“取消”按钮。
                  showLoaderOnConfirm: true,
                  allowOutsideClick: true, //设置为true，用户点击弹窗外部可关闭弹窗
                },
                function(res){ 
                  setTimeout(function(){ 
                    if(res) {  
                            //实际使用过程中将此处换成ajax代码即可  
                            $.getJSON("$editHtml"+id,function (data) {
                                if (data['status']>0){
                                    var name='#brand'+id;
                                    $(name).remove();
                                    swal({  
                                    type: 'success',  
                                    title: '还原成功',   
                                    confirmButtonText: '确定',  
                                    confirmButtonColor: '#4cd964'  
                            }); 
                                }
                                else{
                                   swal({  
                                    type: 'error',  
                                    title: '还原失败',   
                                    confirmButtonText: '确定',  
                                    confirmButtonColor: '#4cd964'  
                            }); 
                                }
                            })
                        } 
                  }, 500);
                });
    });
    
JS;
$this->registerJs($js);
?>