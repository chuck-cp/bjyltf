<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemBanner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget'); ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'target')->textInput(['maxlength' => true]) ?>

    <?/*= $form->field($model, 'sort')->textInput() */?>

    <?=$form->field($model, 'type')->dropDownList(['1'=>'首页banner','2'=>'广告页banner'])?>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <input type="hidden" name="filename" value="system/banner">
    <?php ActiveForm::end(); ?>
</div>
