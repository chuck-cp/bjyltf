<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['invoice'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'starts_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label('申请时间：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'ends_at')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker mtop22'])->label(false);?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'member_name')->textInput(['class'=>'form-control'])->label('申请人：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'member_phone')->textInput(['class'=>'form-control'])->label('申请人联系方式：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'invoice_title')->textInput(['class'=>'form-control'])->label('开票公司名称：');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'status')->dropDownList(['1'=>'申请中','2'=>'已开票'],['class'=>'form-control', 'prompt'=>'全部'])->label('发票状态：');?>
        </div>
        <div class="form-group col-xs-2">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary mtop22']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
