<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\account\models\OrderBrokerage;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salesmanpay'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <?= $form->field($model, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label('提交时间'); ?>
                    <?= $form->field($model, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
                </td>
                <td>
                    <?=$form->field($model,'member_id')->textInput(['class' => 'form-control'])->label('人员ID'); ?>
                </td>
                <td>
                    <?=$form->field($model,'order_code')->textInput(['class' => 'form-control'])->label('订单号'); ?>
                </td>
                <td>
                    <?=$form->field($model,'man_name')->textInput(['class' => 'form-control'])->label('姓名'); ?>
                </td>
                <td>
                    <?=$form->field($model,'man_mobile')->textInput(['class' => 'form-control'])->label('账号'); ?>
                </td>
                <td>
                    <?= $form->field($model, 'part_time_order')->dropDownList(['0'=>'在职业务员','1'=>'广告业务合作人'],['class'=>'form-control','prompt'=>'全部'])->label('人员角色'); ?>
                </td>
                <td>
                    <br />
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                    <?= Html::submitButton('导出', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]) ?>
                </td>
            </tr>
        </table>
        <!--<div class="col-xs-2 form-group">
            <?/*= $form->field($model, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('提交时间'); */?>
        </div>
        <div class="col-xs-2 form-group">
            <?/*= $form->field($model, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); */?>
        </div>
        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'order_code')->textInput(['class' => 'form-control fm'])->label('订单号'); */?>
        </div>
        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'man_name')->textInput(['class' => 'form-control fm'])->label('业务合作人姓名'); */?>
        </div>
        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'man_mobile')->textInput(['class' => 'form-control fm'])->label('业务合作人账号'); */?>
        </div>
        <div class="col-xs-3 form-group" style="margin-left: 138px;">
            <?/*= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) */?>
            <?/*= Html::resetButton('重置', ['class' => 'btn btn-default']) */?>
            <?/*= Html::submitButton('导出', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]) */?>
        </div>-->
    </div>
    <?php ActiveForm::end(); ?>
</div>
<style type="text/css">
    /*#logpaymentsearch-pay_type{*/
        /*margin-top: -13px;*/
        /*margin-left: -19px;*/
    /*}*/
</style>
