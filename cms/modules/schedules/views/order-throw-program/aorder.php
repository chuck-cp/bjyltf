<?php

use yii\helpers\Html;
use cms\modules\member\models\Order;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');

$this->title = '广告审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="member-index">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'order_code',
            'salesman_mobile',
            'salesman_name',
            'rate',
            'advert_name',
            'advert_time',
            [
                'label' => '投放日期',
                'value' =>function($model){
                    return $model->orderDate['start_at']?$model->orderDate['start_at'].'至'.$model->orderDate['end_at']:'';

                }
            ],
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
                        return Html::a('查看',['/examine/order/view','id'=>$model->id],['target'=>'_blank']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>

