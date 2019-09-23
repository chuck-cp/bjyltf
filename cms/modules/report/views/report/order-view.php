<?php

use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\member\models\OrderDate;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <?php echo $this->render('layout/tab',['id'=>$model->id]);?>



    <div class="container">
        <h4 style="font-weight: bold">订单详情</h4>
        <br /><br />

        <h5 style="font-weight: bold">付款信息</h5>
        <table class="table table-bordered">
            <input type="hidden" class="orderid" name="order_id" value="<?=Html::encode($model->id)?>"/>
            <tr>
                <td>订单号</td>
                <td>付款类型</td>
                <td>付款日期</td>
                <td>总费用</td>
                <td>实付金额</td>
            </tr>
            <tr>
                <td><?=Html::encode($model->order_code)?></td>
                <td><?=Html::encode($model->payment_type==1?'全款':'预付款')?></td>
                <td><?=Html::encode($model->payment_at)?></td>
                <td><?=Html::encode($model->order_price)?></td>
                <td><?=Html::encode($model->deal_price)?></td>
            </tr>

        </table>
        <br /><br />

        <h5 style="font-weight: bold">购买详情</h5>
        <table class="table table-bordered">
            <input type="hidden" class="orderid" name="order_id" value="<?=Html::encode($model->id)?>"/>
            <tr>
                <td>用户</td>
                <td>手机号</td>
                <td>业务合作人</td>
                <td>广告位</td>
                <td>广告时长</td>
                <td>投放频次</td>
                <td>投放日期</td>
            </tr>
            <tr>
                <td><?=Html::encode($model->member_name)?></td>
                <td><?=Html::encode($model->member_mobile)?></td>
                <td><?=Html::encode($model->salesman_name)?><a target="_blank" href="<?=Url::to(['/member/member/view', 'id'=>$model->salesman_id])?>">&nbsp;&nbsp;查看</a></td>
                <td><?=Html::encode($model->advert_name)?></td>
                <td><?=Html::encode($model->advert_time)?></td>
                <td><?=Html::encode($model->rate)?></td>
                <td><?=Html::encode(OrderDate::getOrderDate($model->id))?></td>
            </tr>
            <tr>
                <td>投放地区：</td>
                <td colspan="6">
                    <?if($model->deal_price == 0):?>
                        <a href="javascript:void(0);">无购买地区</a>
                    <? else:?>
                        <a target="_blank" href="<?=Url::to(['/report/report/schedule', 'id'=>$model->id])?>">点击查看投放地区列表</a>
                    <?endif;?>
                </td>
            </tr>
        </table>
        <!--<table class="table table-bordered">
            <input type="hidden" class="orderid" name="order_id" value="<?/*=Html::encode($model->id)*/?>"/>
            <tr>
                <td>订单号：</td>
                <td>
                    <?/*=Html::encode($model->order_code)*/?>
                </td>
                <td>广告购买人：</td>
                <td>
                    <?/*=Html::encode($model->member_name)*/?>
                </td>
                <td>购买人所在区域：</td>
                <td>
                    <?/*=Html::encode($model->area_name)*/?>
                </td>
            </tr>
            <tr>
                <td>业务合作人：</td>
                <td>
                    <?/*=Html::encode($model->salesman_name)*/?> <a target="_blank" href="<?/*=Url::to(['/member/member/view', 'id'=>$model->salesman_id])*/?>">查看</a>
                </td>
                <td>广告位：</td>
                <td>
                    <?/*=Html::encode($model->advert_name)*/?>
                </td>
                <td>广告时长：</td>
                <td>
                    <?/*=Html::encode($model->advert_time)*/?>
                </td>
            </tr>
            <tr>
                <td>投放频次：</td>
                <td>
                    <?/*=Html::encode($model->rate)*/?>
                </td>
                <td>购买天数：</td>
                <td>
                    <?/*=Html::encode($model->total_day)*/?>
                </td>
                <td>投放开始日期：</td>
                <td>
                    <?/*=Html::encode(OrderDate::getOrderDate($model->id))*/?>
                </td>
            </tr>
            <tr>
                <td>投放地区：</td>
                <td colspan="5">
                    <?/*if($model->deal_price == 0):*/?>
                        <a href="javascript:void(0);">无购买地区</a>
                    <?/* else:*/?>
                        <a target="_blank" href="<?/*=Url::to(['/report/report/schedule', 'id'=>$model->id])*/?>">点击查看投放地区列表</a>
                    <?/*endif;*/?>
                </td>
            </tr>
        </table>-->
    </div>

</div>
