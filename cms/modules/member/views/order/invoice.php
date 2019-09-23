<?php

use yii\helpers\Html;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '发票申请';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php echo $this->render('_invoicesearch', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '申请时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '申请人',
                'value' => function($searchModel){
                    return $searchModel->member_name;
                }
            ],
            [
                'label' => '申请人联系方式',
                'value' => function($searchModel){
                    return $searchModel->member_phone;
                }
            ],
            [
                'label' => '发票抬头',
                'value' => function($searchModel){
                    return $searchModel->invoice_title;
                }
            ],
            [
                'label' => '纳税人识别号',
                'value' => function($searchModel){
                    return empty($searchModel->taxplayer_id)?'--':$searchModel->taxplayer_id;
                }
            ],
            [
                'label' => '实付金额',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->invoice_value);
                }
            ],
            [
                'label' => '总费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->order_price);
                }
            ],
            [
                'label' => '付款状态',
                'value' => function($searchModel){
                    if($searchModel->status==1){
                        return '申请中';
                    }else if( $searchModel->status==2){
                        return '已开票';
                    }else{
                        return '未设置';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{invoice} {logistics}',
                'buttons' => [
                        'invoice' => function($url,$searchModel){
                            return html::a('发票信息','javascript:void(0);',['class'=>'invoice','id'=>$searchModel->id]);
                        },
                        'logistics' => function($url,$searchModel){
                            return html::a('物流信息','javascript:void(0);',['class'=>'logistics','id'=>$searchModel->id]);
                        },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.invoice').click(function () {
            var id = $(this).attr('id');
            var pageup = layer.open({
                type: 2,
                title: '开票信息',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '80%'],
                content: '<?=\yii\helpers\Url::to(['/member/order/invoiceinformation'])?>&id='+id
            });
        })
        $('.logistics').click(function () {
            var id = $(this).attr('id');
            var pageup = layer.open({
                type: 2,
                title: '发票物流信息',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '60%'],
                content: '<?=\yii\helpers\Url::to(['/member/order/logisticsinformation'])?>&id='+id
            });
        })
    })
</script>
<style>
    .shuru {text-align: center; font-weight:bold}
    .bh {margin-left:120px;margin-top:30px;}
</style>