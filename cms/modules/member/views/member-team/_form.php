<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeam */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'live_area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'live_area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'live_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_area_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_shop_number')->textInput() ?>

    <?= $form->field($model, 'not_install_shop_number')->textInput() ?>

    <?= $form->field($model, 'not_assign_shop_number')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
