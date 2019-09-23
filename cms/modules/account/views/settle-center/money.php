<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use \cms\models\AdvertPosition;
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
        <input type="text" value="<?= Html::encode($model['order_code'])?>" name="order_code">
        <input type="text" value="<?= Html::encode($model['pay_style'])?>" name="pay_style">
        <input type="text" value="<?= Html::encode($model['payment_status'])?>" name="payment_status">
        <input type="text" value="<?= Html::encode($model['id'])?>" name="id">
        <tr>
            <td style="width: 95px;">应收金额：</td>
            <td colspan="3">
                <?= Html::encode(\common\libs\ToolsClass::priceConvert($model['price']))?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">实收金额：</td>
            <td colspan="3">
                <input type="text" name="count_payment_price" class = 'form-control fm'>
            </td>
        </tr>
        <tr >
            <td><?= Html::Button('提交',['class'=>'btn btn-primary'])?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
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
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.btn-primary').bind('click',function(){
            var order_code=$('input[name="order_code"]').val();
            var pay_style=$('input[name="pay_style"]').val();
            var payment_status=$('input[name="payment_status"]').val();
            var count_payment_price=$('input[name="count_payment_price"]').val();
            var id=$('input[name="id"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['moneyajax'])?>',
                type: 'GET',
                dataType : 'json',
                data : {'order_code':order_code, 'pay_style': pay_style,'payment_status':payment_status,'count_payment_price':count_payment_price,'id':id},
                success:function(phpdata){
                    alert(phpdata);
                    /*if(phpdata==1){
                        layer.msg('提交成功！');
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }
                    else if(phpdata==3){
                        layer.msg('实收金额超出应收金额！');
                    }
                    else if(phpdata==2){
                        layer.msg('提交失败！');
                        layer.closeAll('page');
                    }*/
                },
                error:function(){
                    layer.msg('操作失败！');
                }
            });
        })

    })
</script>
