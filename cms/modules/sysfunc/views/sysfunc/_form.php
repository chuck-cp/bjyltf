<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sysfunc\models\SystemFunction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-function-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget'); ?>

    <?= $form->field($model,'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1'=>'开启','2'=>'关闭','3'=>'内部管理员可见','签到管理员可见']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <input type="hidden" name="filename" value="sysfunc">

    <?php ActiveForm::end(); ?>

</div>
