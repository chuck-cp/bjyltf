<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use cms\modules\examine\models\ShopLogistics;

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
<div>
<?php

?>
    <?php $form = ActiveForm::begin([
    'action' => ['addscreen'],
    'method' => 'post',
]);?>
    <table class="table table-hover" >
        <input type="hidden" name="shopid" value="<?=Html::encode($shop_id)?>"/>
        <input type="hidden" name="types" value="upwuliu"/>
        <tr>
            <td align="center">物流名称：</td>
            <td><?= $form->field($wlmodel, 'name')->dropDownList(ShopLogistics::getLogistList('all'),['class'=>'form-control','prompt'=>'请选择'])->label(false) ?></td>
        </tr>
        <tr>
            <td align="center">订单编号：</td>
            <td><?= $form->field($wlmodel, 'logistics_id')->textInput()->label(false) ?></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><?= Html::Button('提交',['class'=>'btn btn-primary'])?></td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
    </html >
<?php $this->endPage() ?>

<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.btn-primary').click(function(){
        var wlname = $('#shoplogistics-name').val();
        var wlid = $('#shoplogistics-logistics_id').val();
        if(!wlname || !wlid){
            layer.alert("请填写物流信息！");
            return false;
        }else{
            $('#w0').submit();
        }
    })
</script>
