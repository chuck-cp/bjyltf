<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="member-search">
    <?php $form = ActiveForm::begin([
//        'action' => [''],
        'method' => 'post',
    ]);     ?>
    <table class="table table-hover" >
        <tr>
            <?= $form->field($model,'id')->hiddenInput(['id'=>$model->id,'readonly'=>false])->label(false)?>
            <td style="width: 95px;">*用户名:</td>
            <td colspan="3">
                <?= $form->field($model,'username')->textInput([ 'readonly' => 'true'])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*新密码:</td>
            <td colspan="3">
                <?= $form->field($model,'new_password')->passwordInput([])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*确认密码:</td>
            <td colspan="3">
                <?= $form->field($model,'old_cipher')->passwordInput([])->label(false)?>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="4"><?= Html::Button('提交',['class'=>'btn btn-primary'])?></td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        $(".btn-primary").click(function(){
            var data=$("#w0").serialize();
            var id=$('input[name="CustomUser[id]"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['resetpw'])?>&id='+id,
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
    })
</script>

<style type="text/css">
    .radio, .checkbox {
        display: inline-block;
        min-height: 20px;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-left: 20px;
        width: 100px;
        vertical-align: bottom;
    }
</style>
