<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salesman_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salesman_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'custom_service_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'custom_service_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_day')->textInput() ?>

    <?= $form->field($model, 'payment_type')->textInput() ?>

    <?= $form->field($model, 'payment_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_at')->textInput() ?>

    <?= $form->field($model, 'overdue_number')->textInput() ?>

    <?= $form->field($model, 'screen_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'advert_id')->textInput() ?>

    <?= $form->field($model, 'advert_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'advert_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'payment_status')->textInput() ?>

    <?= $form->field($model, 'examine_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
