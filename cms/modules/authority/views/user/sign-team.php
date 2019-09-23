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
<div class="member-search" style="overflow: hidden">
    <?php $form = ActiveForm::begin([
//        'action' => [''],
        'method' => 'post',
    ]);     ?>
    <?= $form->field($model,'id')->hiddenInput(['id'=>$model->id,'readonly'=>false])->label(false)?>
    <table class="table table-hover" style="float: left;width: 50%">
        <tr style="text-align: center;">
            <td colspan="2">业务</td>
        </tr>
        <? foreach ($teamModelyw as $kyw=>$vyw):?>
        <tr style="text-align: center;">
            <td><input type="checkbox" name="User[sign_team][]"  <?if(in_array($vyw['id'],explode(',',$model->sign_team))):?>checked="checked"<?endif;?> value="<?echo $vyw['id']?>"></td>
            <td><?=Html::encode($vyw['team_name']);?></td>
        </tr>
        <?endforeach;?>
    </table>
    <table class="table table-hover" style="float: left;width: 50%">
        <tr style="text-align: center;">
            <td colspan="2">维护</td>
        </tr>
        <? foreach ($teamModelwh as $kwh=>$vwh):?>
        <tr style="text-align: center;">
            <td><input type="checkbox" name="User[sign_team][]"  <?if(in_array($vwh['id'],explode(',',$model->sign_team))):?>checked="checked"<?endif;?> value="<?echo $vwh['id']?>"></td>
            <td><?=Html::encode($vwh['team_name']);?></td>
        </tr>
        <?endforeach;?>
    </table>
    <br>
    <p style="text-align: center;clear: both;"><?= Html::Button('提交',['class'=>'btn btn-primary'])?></p>
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
            console.log(data);
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['sign-team'])?>',
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
