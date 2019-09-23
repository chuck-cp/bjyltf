<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopContract */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-contract-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->textInput() ?>

    <?= $form->field($model, 'shop_type')->textInput() ?>

    <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cabinet_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'examine_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
