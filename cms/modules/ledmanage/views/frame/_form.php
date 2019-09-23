<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDeviceFrame */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-device-frame-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_material')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'office_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receive_office_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manufactor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'batch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiving_at')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_output')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'spec')->textInput() ?>

    <?= $form->field($model, 'goods_receipt_at')->textInput() ?>

    <?= $form->field($model, 'stock_out_at')->textInput() ?>

    <?= $form->field($model, 'storehouse')->textInput() ?>

    <?= $form->field($model, 'out_manager')->textInput() ?>

    <?= $form->field($model, 'in_manager')->textInput() ?>

    <?= $form->field($model, 'receive_member_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
