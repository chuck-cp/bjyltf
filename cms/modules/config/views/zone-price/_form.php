<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemZonePrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-zone-price-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true])->label('店铺价格') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'month_price')->textInput(['maxlength' => true])->label('每月价格') ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group" style="margin-left: 15px;">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
