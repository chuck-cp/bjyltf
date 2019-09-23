<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\ShopUpdateRecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-update-record-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identity_card_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registration_mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_shop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_apply_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_identity_card_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_registration_mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'images')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'examine_status')->textInput() ?>

    <?= $form->field($model, 'create_user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
