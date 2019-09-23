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
        'action' => ['offline'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>提交时间</p>
                    <?= $form->field($model, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); ?>
                    <?= $form->field($model, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
                </td>
                <td>
                    <p>交易码</p>
                    <?=$form->field($model,'payment_code')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>
                <td>
                    <p>订单号</p>
                    <?=$form->field($model,'order_code')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>

                <td>
                    <p>业务合作人姓名</p>
                    <?=$form->field($model,'salesman_name')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>
                <td>
                    <p>广告对接人姓名</p>
                    <?=$form->field($model,'custom_service_name')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>

                <td>
                    <p>用户ID</p>
                    <?=$form->field($model,'member_id')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>业务员合作人ID</p>
                    <?=$form->field($model,'salesman_id')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>
                <td>
                    <p>广告对接人ID</p>
                    <?=$form->field($model,'custom_member_id')->textInput(['class' => 'form-control'])->label(false); ?>
                </td>
                <td>
                    <p>收款状态</p>
                    <?= $form->field($model, 'pay_status')->dropDownList(['0'=>'未付款','1'=>'已付款'],['class'=>'form-control','prompt'=>'全部'])->label(false); ?>
                </td>
                <td>
                    <p>支付类型</p>
                    <?= $form->field($model, 'pay_style')->dropDownList(LogPayment::getPayStyle(),['class'=>'form-control','prompt'=>'全部'])->label(false); ?>

                </td>
                <td colspan="4"><p></p>
                    <br />
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                    <?= html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                </td>


            </tr>
        </table>
        <!--<div class="col-xs-2 form-group">
            <?/*= $form->field($model, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('提交时间'); */?>
        </div>
        <div class="col-xs-2 form-group">
            <?/*= $form->field($model, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); */?>
        </div>

        <div class="col-xs-2 form-group">
            <?/*=$form->field($model,'payment_code')->textInput(['class' => 'form-control fm'])->label('交易码'); */?>
        </div>
        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'order_code')->textInput(['class' => 'form-control fm'])->label('订单号'); */?>
        </div>
        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'salesman_name')->textInput(['class' => 'form-control fm'])->label('业务合作人姓名'); */?>
        </div>

        <div class="col-xs-3 form-group">
            <?/*=$form->field($model,'custom_service_name')->textInput(['class' => 'form-control fm'])->label('广告对接人姓名'); */?>
        </div>
        <div class="col-xs-2 form-group" style="margin-left: -50px;">
            <?/*= $form->field($model, 'pay_status')->dropDownList(['0'=>'未付款','1'=>'已付款'],['class'=>'form-control fm','prompt'=>'全部'])->label('收款状态'); */?>
        </div>
        <div class="col-xs-3 form-group" >
            <?/*= $form->field($model, 'pay_style')->dropDownList(LogPayment::getPayStyle(),['class'=>'form-control fm','prompt'=>'全部'])->label('支付类型'); */?>
        </div>
        <div class="col-xs-3 form-group" style="margin-left: 138px;">

        </div>-->
    </div>



    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">

    .search tr td{
        background-color: #f2f2f2;
    }
</style>
