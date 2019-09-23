<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopPark */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-shop-park-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'led_screen_number')->textInput() ?>

    <?= $form->field($model, 'poster_screen_number')->textInput() ?>

    <?= $form->field($model, 'shop_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_image')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'poster_create_at')->textInput() ?>

    <?= $form->field($model, 'install_finish_at')->textInput() ?>

    <?= $form->field($model, 'poster_install_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_install_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_install_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_install_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_install_finish_at')->textInput() ?>

    <?= $form->field($model, 'poster_last_examine_user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_examine_user_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_examine_user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_examine_number')->textInput() ?>

    <?= $form->field($model, 'poster_examine_status')->textInput() ?>

    <?= $form->field($model, 'poster_examine_at')->textInput() ?>

    <?= $form->field($model, 'contract_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
