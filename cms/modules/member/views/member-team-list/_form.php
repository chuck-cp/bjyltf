<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeamList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-team-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'install_shop_number')->textInput() ?>

    <?= $form->field($model, 'install_screen_number')->textInput() ?>

    <?= $form->field($model, 'wait_shop_number')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
