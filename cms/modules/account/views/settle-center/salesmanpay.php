<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
use cms\modules\account\models\LogPayment;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\LogPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '业务合作人支出';
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<div class="log-payment-index">
    <?php echo $this->render('_manpaysearch', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => '订单编号',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['order_code'];
                }
            ],
            [
                'label' => '人员ID',
                'value' => function($searchModel){
                    return $searchModel->member_id;
                }
            ],
            'member_name',
            'member_mobile',
            [
                'label' => '人员角色',
                'value' => function($searchModel){
                    return $searchModel->orderInfo['part_time_order']==1?'广告业务合作人':'在职业务员';
                }
            ],
            [
                'label' => '订单总额',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->orderInfo['order_price']);
                }
            ],
//            [
//                'label' => '订单时间',
//                'value' => function($searchModel){
//                    return $searchModel->orderInfo['create_at'];
//                }
//            ],
            [
                'label' => '广告成交金额',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->orderInfo['deal_price']);
                }
            ],
            [
                'label' => '广告佣金',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->member_price);
                }
            ],
            [
                'label' => '伙伴提成',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->member_parent_price);
                }
            ],
            [
                'label' => '区域配合费',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->cooperate_money);
                }
            ],
            [
                'label' => '总支出',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->total);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return html::a('查看','javascript:void(0);',['class'=>'paylist','id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $('.paylist').click(function () {
        var id = $(this).attr('id');
        var pg = layer.open({
            type: 2,
            title: '详情',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '95%'],
            content: '<?=\yii\helpers\Url::to(['/account/settle-center/manpay'])?>&id='+id
        });
    })
</script>
