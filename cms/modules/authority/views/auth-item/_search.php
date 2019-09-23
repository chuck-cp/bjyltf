<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\authority\models\search\AuthRuleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="col-xs-2 form-group" style="padding-right: 0px;">
        <?=$form->field($model,'name')->textInput(['class'=>'form-control fm'])->label('角色名称');?>
    </div>
    <div class="col-xs-2 form-group" style="padding-right: 0px;">
        <?=$form->field($model,'description')->textInput(['class'=>'form-control fm'])->label('角色说明');?>
    </div>


    <?//= $form->field($model, 'name') ?>

    <?//= $form->field($model, 'type') ?>

    <?//= $form->field($model, 'description') ?>

    <?//= $form->field($model, 'rule_name') ?>

    <?//= $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>