<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\OrderThrowProgram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-throw-program-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'area_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'advert_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
