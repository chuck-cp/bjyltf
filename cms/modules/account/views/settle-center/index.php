<?php

use yii\helpers\Html;
use cms\core\CmsGridView;
use cms\modules\account\models\LogPayment;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\LogPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收款';
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<div class="log-payment-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <table class="table table-bordered" style="width: 60%;text-align: center;">
        <tr>
            <th style="text-align: center;">类型</th>
            <th style="text-align: center;">广告总收入</th>
            <th style="text-align: center;">业务合作人支出</th>
            <th style="text-align: center;">广告收益</th>
        </tr>
        <tr>
            <td>金额（元）</td>
            <td><?=Html::encode($totalMoney['total'])?></td>
            <td><?=Html::encode($totalMoney['adv_expend'])?></td>
            <td><?=Html::encode($totalMoney['margin'])?></td>
        </tr>
    </table>
    <div class="aa">
    <?= CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        //'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'id',
            'pay_at',
            [
                'label' => '用户ID',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['member_id'];
                }
            ],
            //用户账户
            [
                'label' => '用户姓名',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['member_name'];
                }
            ],
            [
                'label' => '支付类型',
                'value' => function($searchModel){
                    return LogPayment::getPayStyle(true,$searchModel->pay_style);
                }
            ],
            'order_code',
            [
                'label' => '订单总额',
                'value' => function($searchModel){
                    if($searchModel->pay_style ==2){
                        return '---';
                    }
                    return \common\libs\ToolsClass::priceConvert($searchModel->orderInfo['order_price']);
                }
            ],
            /*[
                'label' => '优惠方式',
                'value' => function($searchModel){
                    if($searchModel->orderInfo['preferential_way']){
                        return $searchModel->orderInfo['preferential_way'];
                    }else{
                        return '无优惠';
                    }
                }
            ],*/
            [
                'label' => '最终价格',
                'value' => function($searchModel){
                    return \common\libs\ToolsClass::priceConvert($searchModel->orderInfo['final_price']);
                }
            ],
            [
                'label' => '本次收款金额',
                'value' => function($searchModel){
                    return \common\libs\ToolsClass::priceConvert($searchModel->price);
                }
            ],
            'serial_number',
            [
                'label' => '支付方式',
                'value' => function($searchModel){
                    return LogPayment::getPayType(true,$searchModel->pay_type);
                }
            ],
            'other_serial',
            //业务合作人支出
            [
                'label' => '业务合作人支出',
                'value' => function($searchModel){
                    if($searchModel->pay_style ==2){
                        return '---';
                    }
                    return \common\libs\ToolsClass::priceConvert($searchModel->brokerage['total']);
                }
            ],
            //广告实际收入
            [
                'label' => '广告实际收入',
                'value' => function($searchModel){
                    if($searchModel->pay_style ==2){
                        return '---';
                    }
                    return \common\libs\ToolsClass::priceConvert($searchModel->brokerage['real_income']);
                }
            ],
            [
                'label' => '合作人ID',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['salesman_id']?$searchModel->orderInfo['salesman_id']:'';
                }
            ],
            [
                'label' => '合作人',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['salesman_name']?$searchModel->orderInfo['salesman_name']:'';
                }
            ],
            [
                'label' => '合作人手机',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['salesman_mobile']?$searchModel->orderInfo['salesman_mobile']:'';
                }
            ],
            [
                'label' => '对接人ID',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['custom_member_id']?$searchModel->orderInfo['custom_member_id']:'';
                }
            ],
            [
                'label' => '对接人',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['custom_service_name']?$searchModel->orderInfo['custom_service_name']:'';
                }
            ],
            [
                'label' => '订单备注',
                'value' => function($model){
                    return $model->orderInfo['remarks'];
                }
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
<style type="text/css">
    .aa{
        white-space: nowrap; overflow: hidden; overflow-x: scroll; -webkit-backface-visibility: hidden; -webkit-overflow-scrolling: touch;
    }
    .table{word-wrap:break-word; word-break:break-all;}
</style>
