<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\sign\models\SignTeam */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sign-team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_member_number')->textInput() ?>

    <?= $form->field($model, 'team_manager_number')->textInput() ?>

    <?= $form->field($model, 'sign_interval_time')->textInput() ?>

    <?= $form->field($model, 'sign_qualified_number')->textInput() ?>

    <?= $form->field($model, 'first_sign_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_type')->textInput() ?>

    <?= $form->field($model, 'team_member_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
