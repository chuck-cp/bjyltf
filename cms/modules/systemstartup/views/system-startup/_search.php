<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\systemstartup\models\search\SystemStartupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-startup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'version') ?>

    <?= $form->field($model, 'visibility') ?>

    <?= $form->field($model, 'start_at') ?>

    <?= $form->field($model, 'end_at') ?>

    <?php // echo $form->field($model, 'start_pic') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'create_user_id') ?>

    <?php // echo $form->field($model, 'create_user_name') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
