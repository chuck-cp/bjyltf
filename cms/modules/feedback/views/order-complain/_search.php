<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\feedback\models\search\FeedbackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-search">

    <?php
    $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'order_code')->textInput(['class'=>'form-control fm'])->label('订单号');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'complain_member_name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'create_at')->dropDownList(['1'=>'正序排列','2'=>'倒序排列'],['prompt'=>'全部','class'=>'form-control fm'])->label('提交日期');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 115px;display: inline-block;}
</style>
