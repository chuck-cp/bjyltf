<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\AdvertPosition;
use cms\modules\member\models\Order;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'order_code')->textInput(['class'=>'form-control'])->label('订单号：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'phone')->textInput(['class'=>'form-control'])->label('电话：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'salesman_name')->textInput(['class'=>'form-control'])->label('业务合作人：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'advert_id')->dropDownList(AdvertPosition::getAllAdvertPos(),['class'=>'form-control', 'prompt'=>'全部'])->label('广告位：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'custom_service_name')->textInput(['class'=>'form-control'])->label('广告对接人：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'payment_type')->dropDownList(['1'=>'全额支付','2'=>'定金支付'],['class'=>'form-control','prompt'=>'全部'])->label('付款类型：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'payment_status')->dropDownList(Order::paymentStatus(),['class'=>'form-control', 'prompt'=>'全部'])->label('付款状态：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'starts_at')->textInput(['class'=>'form-control datepicker'])->label('首付款日期：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'ends_at')->textInput(['class'=>'form-control datepicker mtop22'])->label(false);?>
        </div>
        <div class="form-group col-xs-2">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary mtop22']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
