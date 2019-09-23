<?php

use yii\helpers\Html;
use cms\modules\member\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            //'member_id',
            //'member_name',

            //'custom_service_name',
            //'custom_service_mobile',
            'order_code',
            'salesman_mobile',
            'salesman_name',
            //'order_price',
            //'unit_price',
            //'total_day',
            //'payment_type',
            //'payment_price',
            //'payment_at',
            //'overdue_number',
            //'screen_number',

            //'area_name',
            //'advert_id',
            'advert_name',
            'advert_time',
            'rate',
            [
                'label' => '投放日期',
                'value' =>function($model){
                    return $model->orderDate['start_at']?$model->orderDate['start_at'].'至'.$model->orderDate['end_at']:'';
                    //return \cms\modules\examine\models\OrderDate::getDeliveryDate($model->id);
                }
            ],
            //'payment_status',
            [
                'label' => '投放状态',
                'value' => function($model){
                    return Order::getOrderStatus('examine_status',$model->examine_status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('查看详情',['order/view','id'=>$model->id]);
                    }
                ],
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

