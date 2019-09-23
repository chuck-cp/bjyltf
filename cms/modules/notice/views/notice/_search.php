<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="system-notice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'title')->textInput(['class'=>'form-control fm'])->label('标题');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'create_user')->textInput(['class'=>'form-control fm'])->label('发布者');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'create_at')->dropDownList(['1'=>'倒序排列','2'=>'正序排列'],['class'=>'form-control fm','prompt'=>'全部'])->label('发布日期');?>
        </div>
        <div class="col-xs-3 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
            <?=Html::a('发布公告',['/notice/notice/create'],['class' => 'btn btn-success','target'=>'_blank'])?>
        </div>
    </div>
<!--    --><?//= $form->field($model, 'title') ?>
<!---->
<!--    --><?//= $form->field($model, 'create_user') ?>
<!---->
<!--    --><?//= $form->field($model, 'create_at') ?>
<!---->
<!--    --><?php //// echo $form->field($model, 'create_at') ?>
<!---->
<!--    <div class="form-group">-->
<!--        --><?//= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
<!--    </div>-->

    <?php ActiveForm::end(); ?>

</div>
