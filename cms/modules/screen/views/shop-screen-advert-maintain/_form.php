<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\screen\models\ShopScreenAdvertMaintain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-screen-advert-maintain-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mongo_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screen_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'install_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_finish_at')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'assign_at')->textInput() ?>

    <?= $form->field($model, 'assign_time')->textInput() ?>

    <?= $form->field($model, 'problem_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'images')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
