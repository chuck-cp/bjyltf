<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '线下汇款配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = \yii\widgets\ActiveForm::begin([
    'action' => [''],
    'method' => 'post',
]); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            收款方姓名：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'system_receiver_name')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 yw">
            收款人地址：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'system_receiver_address')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 yw">
            收款方银行账号：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'system_receiver_bank_number')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 yw">
            收款银行：
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'system_receiver_bank_name')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="xiugaidiv">
<!--        --><?//= Html::Button('修改', ['class' => 'btn btn-primary xiugai']) ?>
<!--    </div>-->
<!--    <div class="baocundiv" style="display: none;">-->
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary baocun']) ?>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end(); ?>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
//    $(".xiugai").click(function(){
//        $('.form-control').removeAttr("readonly");
//        $(".xiugaidiv").hide();
//        $(".baocundiv").show();
//    });
</script>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
    .form-control{width:450px;}
</style>