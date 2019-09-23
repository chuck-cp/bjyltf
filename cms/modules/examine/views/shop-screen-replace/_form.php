<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopScreenReplace */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-screen-replace-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_finish_at')->textInput() ?>

    <?= $form->field($model, 'install_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'replace_screen_number')->textInput() ?>

    <?= $form->field($model, 'create_user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'assign_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
