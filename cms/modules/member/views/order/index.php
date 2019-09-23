<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\member\models\Member;
use cms\modules\member\models\Order;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '广告查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'order_code',
            //'member_name',
            [
                'label' => '电话',
                'value' => function($searchModel){
                    return $searchModel->memberInfo['mobile']?$searchModel->memberInfo['mobile']:'---';
                    //return  Member::getNameById($searchModel->member_id,'mobile');
                }
            ],
            'salesman_name',
            //'salesman_mobile',
            'custom_service_name',
            //'custom_service_mobile',
            'advert_name',
            'advert_time',
            'rate',
            //'payment_type',
            [
                'label' => '付款类型',
                'value' => function($searchModel){
                    return Order::getOrderStatus('payment_type',$searchModel->payment_type);
                }
            ],
            /*[
                'label' => '优惠方式',
                'value' => function($searchModel){
                    if($searchModel->preferential_way){
                        return $searchModel->preferential_way;
                    }else{
                        return '无优惠';
                    }
                }
            ],*/
            [
                'label' => '订单状态',
                'value' => function($searchModel){
                    return Order::getOrderStatus('payment_status',$searchModel->payment_status);
                }
            ],
            [
                'label' => '订单价格',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->order_price);
                }
            ],
            [
                'label' => '最终价格',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->final_price);
                }
            ],
           // 'payment_at',
            [
                'label' => '首付款日期',
                'value' => function($searchModel){
                    if($searchModel->payment_status == -1 || $searchModel->payment_status == 0){
                        return '---';
                    }else{
                        return $searchModel->payment_at;
                    }
                }
            ],
            [
                'label' => '首付金额',
                'value' => function($searchModel){
                    if($searchModel->payment_type == 2){
                        return ToolsClass::priceConvert($searchModel->payment_price);
                    }else{
                        return '---';
                    }
                }
            ],
            [
                'label' => '尾款日期',
                'value' => function($searchModel){
                    if($searchModel->payment_type == 2){
                        return $searchModel->last_payment_at;
                    }else{
                        return '---';
                    }
                }
            ],
            [
                'label' => '尾款',
                'value' => function($searchModel){
                    if($searchModel->payment_type == 2){
                        return ToolsClass::priceConvert($searchModel->final_price - $searchModel->payment_price);
                    }else{
                        return '---';
                    }

                }
            ],
            [
                'label' => '未付款',
                'value' => function($searchModel){
                    if($searchModel->payment_status < 1){
                        return ToolsClass::priceConvert($searchModel->final_price);
                    }elseif ($searchModel->payment_status == 2 || $searchModel->payment_status == 1){
                        return ToolsClass::priceConvert($searchModel->final_price - $searchModel->payment_price);
                    }else{
                        return '0.00';
                    }

                }
            ],

            [
                'label' => '投放状态',
                'value' => function($searchModel){
                    if($searchModel->payment_status=='-1' || $searchModel->payment_status=='0'|| $searchModel->payment_status=='-2'|| $searchModel->payment_status=='-3'){
                        return '---';
                    }else{
                        return Order::getOrderExamineStatus($searchModel->examine_status);
                    }

                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template' => '{view}',
                'buttons' => [
                        /*'view' => function($url,$searchModel){
                            return Html::a('查看', 'javascript:void(0);', ['id'=>$searchModel->id,'class'=>'view_this']);
                        }*/
                        'view' => function($url,$searchModel){
                            return Html::a('查看',  ['order/detail','id'=>$searchModel->id]);
                        }
                ],
            ],
            'remarks',
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.view_this').bind('click', function () {
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '订单详情：',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '95%'],
                content: '<?=\yii\helpers\Url::to(['detail'])?>&id='+id //iframe的url
            });
        })
    })

</script>