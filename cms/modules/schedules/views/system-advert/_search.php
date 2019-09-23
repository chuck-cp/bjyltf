<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="screen-run-time-shop-subsidy-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>广告ID</p>
                    <?=$form->field($model,'id')->textInput(['class'=>'form-control fm collection-width'])->label(false);?>
                </td>
                <td>
                    <p>广告名称</p>
                    <?=$form->field($model,'advert_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>店铺名称</p>
                    <?=$form->field($model,'shop_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>广告时长</p>
                    <?=$form->field($model, 'advert_time')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>投放频次</p>
                    <?=$form->field($model, 'throw_rate')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>广告位</p>
                    <?=$form->field($model,'advert_position_key')->dropDownList(['a'=>'A屏广告','b'=>'B屏广告','c'=>'C屏广告','d'=>'D屏广告'],['prompt'=>'全部','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>广告状态</p>
                    <?=$form->field($model,'throw_status')->dropDownList(['0'=>'未推送','1'=>'已推送','2'=>'投放完成'],['prompt'=>'全部','class'=>'form-control fm'])->label(false) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>投放日期</p>
                    <?=$form->field($model,'launch_date')->textInput(['class' => 'form-control datepicker collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>创建时间</p>
                    <?=$form->field($model,'create_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                </td>
                <td>
                    <?=$form->field($model,'create_at_end')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
                </td>
                <td style="padding-top:30px;">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search'])?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
