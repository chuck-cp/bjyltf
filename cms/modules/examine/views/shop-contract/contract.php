<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');

$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="container">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <table class="table table-bordered">
            <tr>
                <td style="text-align: center;">合同编号：</td>
                <td><input type="text" class="danhao" name="contract_number" value="<?php echo $model->contract_number ?>"></td>
            </tr>
            <tr>
                <td style="text-align: center;">柜号：</td>
                <td><input type="text" class="danhao" name="cabinet_number" value="<?php echo $model->cabinet_number ?>"></td>
            </tr>
            <tr style="height: 50px;">
                <td style="text-align: center;">备注：</td>
                <td><textarea cols="50" rows="10" class="danhao" name="description" maxlength="500"><?php echo $model->description ?></textarea></td>
            </tr>
        </table>
        <div style="text-align: center;" id="qrwl">
            <a class="btn btn-primary confirm " id="<?php echo $model['id']?>" >提交</a>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
   .danhao{border-radius:5px;border:1px solid #ddd;}
</style>
<script src="/static/js/common.js"></script>
<script type="text/javascript">
    $('.confirm').on('click',function(){
        var data=$('#w0').serialize();
        var id=$(this).attr('id')
        $.ajax({
            url:'<?=Url::to(['add-contract-id'])?>&id='+id,
            type : 'POST',
            dataType : 'json',
            data : data,
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        parent.location.reload();
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
</script>
