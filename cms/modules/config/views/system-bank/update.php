<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemBank */

$this->title = '修改银行' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '银行管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="system-bank-update">
    <div class="system-bank-form">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model,'bank_logo')->widget('yidashi\uploader\SingleWidget'); ?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
        <input type="hidden" name="filename" value="bank">
        <?php ActiveForm::end(); ?>
    </div>
</div>
