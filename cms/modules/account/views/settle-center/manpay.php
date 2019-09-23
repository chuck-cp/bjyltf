<?php

use yii\helpers\Html;
use cms\modules\account\models\OrderBrokerage;
use common\libs\ToolsClass;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\LogPayment */

//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Log Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-payment-view">
    <table class="table table-hover">
        <tr>
            <td colspan="3" style="text-align: center"><h1>业务合作人支出详情</h1></td>
        </tr>
        <tr>
            <td><h4>业务合作人信息</h4></td>
        </tr>
        <tr>
            <td>业务合作人ID：<?=Html::encode($payinfo['member_id'])?></td>
            <td>业务合作人姓名：<?=Html::encode($payinfo['member_name'])?></td>
            <td>业务合作人电话：<?=Html::encode($payinfo['member_mobile'])?></td>
        </tr>
        <tr>
            <td><h4>订单信息</h4></td>
        </tr>
        <tr>
            <td>订单编号：<?=Html::encode($payinfo['ordercode'])?></td>
            <td>广告总价：<?=Html::encode(ToolsClass::priceConvert($payinfo['orderpay']))?>元</td>
            <td>业务合作人佣金：<?=Html::encode(ToolsClass::priceConvert($payinfo['member_price']))?>元</td>
        </tr>
        <tr>
            <td><h4>提成</h4></td>
        </tr>
        <tr>
            <td>业务合作人(上级)：<?=Html::encode($payinfo['meminfo']['name'])?></td>
            <td>手机号（上级）：<?=Html::encode($payinfo['meminfo']['mobile'])?></td>
            <td>提成（上级）：<?=Html::encode(ToolsClass::priceConvert($payinfo['meminfo']['memprice']))?>元</td>
        </tr>
        <tr>
            <td><h4>配合费</h4></td>
        </tr>
        <tr>
            <td>总配合费：<?=Html::encode(ToolsClass::priceConvert($payinfo['cooperate_money']))?>元</td>
        </tr>
        <?foreach($payinfo['peihemems'] as $peihek=>$peihev): ?>
            <tr>
                <td>领取配合费人员<?=Html::encode($peihek+1)?>：<?=Html::encode($peihev['peihename'])?></td>
                <td>手机号：<?=Html::encode($peihev['peihemobile'])?></td>
                <td>配合费金额：<?=Html::encode(ToolsClass::priceConvert($peihev['peiheprice']))?>元</td>
            </tr>
        <? endforeach;?>
    </table>
</div>
<style type="text/css">
    .log-payment-view table{ width: 100%}
    .log-payment-view table td{ width: 30%}
    /*.log-payment-view table td:last-child{ width: 40%}*/
</style>