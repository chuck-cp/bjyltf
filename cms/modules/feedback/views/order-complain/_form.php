<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\feedback\models\OrderComplain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-complain-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complain_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complain_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complain_type')->textInput() ?>

    <?= $form->field($model, 'complain_level')->textInput() ?>

    <?= $form->field($model, 'complain_content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
