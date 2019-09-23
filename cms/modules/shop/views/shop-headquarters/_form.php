<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopHeadquarters */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-headquarters-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identity_card_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identity_card_front')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identity_card_back')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registration_mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_licence')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agreement_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
