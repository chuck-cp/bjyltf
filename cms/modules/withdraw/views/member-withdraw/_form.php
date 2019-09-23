<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\withdraw\models\MemberWithdraw */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-withdraw-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'back_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'back_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payee_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poundage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_balance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'examine_statis')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'account_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
