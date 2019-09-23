<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="row">
    <div class="col-xs-2">
        <label for="">投放日期</label>
        <?= $form->field($model, 'order_date_starts_at')->textInput(['placeholder'=>'投放开始时间','class'=>'form-control datepicker start'])->label(false); ?>
        <?= $form->field($model, 'order_date_ends_at')->textInput(['placeholder'=>'投放结束时间','class'=>'form-control datepicker start'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">订单号<?= $form->field($model, 'order_code')->textInput(['placeholder'=>'订单号','class'=>'form-control'])->label(false); ?>
        </label>
    </div>
    <div class="col-xs-2">
        <label for="">电话<?= $form->field($model, 'salesman_mobile')->textInput(['placeholder'=>'电话','class'=>'form-control'])->label(false); ?>
        </label>
    </div>
    <div class="col-xs-2">
        <label for="">业务员合作人<?= $form->field($model, 'salesman_name')->textInput(['placeholder'=>'业务合作人','class'=>'form-control'])->label(false); ?>
        </label>
    </div>
    <div class="col-xs-2">
        <?= $form->field($model, 'advert_name')->dropDownList(\cms\models\AdvertPosition::getAllAdvertname(),['prompt'=>'全部']);?>
    </div>
    <div class="col-xs-2">
        <label for="">审核状态</label>
        <?= $form->field($model, 'examine_status')->dropDownList(['1'=>'待审核','3'=>'审核通过','2'=>'被驳回'],['prompt'=>'全部'])->label(false); ?>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary submit','name'=>'search','value'=>'1']) ?>
            <?= Html::submitButton('导出', ['class' => 'btn btn-primary submit','name'=>'search','value'=>'0']) ?>
        </div>
    </div>

</div>
<?php ActiveForm:: end()?>

