<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\account\models\LogPayment;
/* @var $this yii\web\View */
/* @var $model cms\modules\account\models\search\LogPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-payment-search">
    <?php $form = ActiveForm::begin([
        'action' => ['collection'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td class="date">
                    <p>收款时间</p>
                    <?=$form->field($model,'pay_at')->textInput(['class'=>'form-control datepicker collection-width','placeholder'=>'开始时间'])->label(false);?>
                    <?=$form->field($model,'pay_at_end')->textInput(['class'=>'form-control datepicker mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
                </td>
                <td>
                    <p>支付类型</p>
                    <?= $form->field($model, 'pay_style')->dropDownList(LogPayment::getPayStyle(),['class'=>'form-control collection-width','prompt'=>'全部'])->label(false); ?>
                </td>
                <td>
                    <p>用户名</p>
                    <?=$form->field($model,'member_name')->textInput(['class' => 'form-control collection-width'])->label(false) ;?>
                </td>
                <td>
                    <p>业务合作人ID</p>
                    <?=$form->field($model,'salesman_id')->textInput(['class' => 'form-control collection-width'])->label(false) ;?>
                </td>
                <td>
                    <p>业务合作人姓名</p>
                    <?=$form->field($model,'salesman_name')->textInput(['class' => 'form-control collection-width'])->label(false) ;?>
                </td>
                <td>
                    <p>用户ID</p>
                    <?=$form->field($model,'member_id')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
                <td>
                    <p>订单号</p>
                    <?=$form->field($model,'order_code')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>广告对接人ID</p>
                    <?=$form->field($model,'custom_member_id')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
                <td>
                    <p>广告对接人手机号</p>
                    <?=$form->field($model,'custom_service_mobile')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
                <td>
                    <p>流水号</p>
                    <?=$form->field($model,'serial_number')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
                <td>
                    <p>第三方支付单号</p>
                    <?=$form->field($model,'other_serial')->textInput(['class' => 'form-control collection-width'])->label(false); ?>
                </td>
                <td>
                    <p>支付方式</p>
                    <div>
                        <?=$form->field($model,'pay_type')->checkboxList(LogPayment::getPayType(),['class' => ''])->label(false); ?>
                    </div>
                </td>
                <td colspan="2">
                   <br />
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>

                    <?=  html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<style type="text/css">

    .search tr td{
        background-color: #f2f2f2;
    }
</style>
