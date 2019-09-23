<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\LogPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'pay_status')->textInput() ?>

    <?= $form->field($model, 'payment_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pay_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
