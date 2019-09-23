<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\Shop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'headquarters_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'headquarters_list_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_screen_number')->textInput() ?>

    <?= $form->field($model, 'screen_number')->textInput() ?>

    <?= $form->field($model, 'error_screen_number')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'screen_status')->textInput() ?>

    <?= $form->field($model, 'delivery_status')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'acreage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_client')->textInput() ?>

    <?= $form->field($model, 'mirror_account')->textInput() ?>

    <?= $form->field($model, 'shop_operate_type')->textInput() ?>

    <?= $form->field($model, 'install_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'examine_user_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'examine_user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
