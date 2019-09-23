<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\shop\models\search\ShopAbnormalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '店铺屏幕信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-abnormal-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '屏幕软件编码',
                'value' => function($searchModel){
                    return $searchModel->software_number;
                }
            ],
            [
                'label' => '店铺名称',
                'value' => function($searchModel) use($shop_name){
                    return $shop_name;
                }
            ],
            [
                'label' => '屏幕运行总时长(小时)',
                'value' => function($model){
                    return round($model->time_sum/60/60,2);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel) use($shop_name){
                        return html::a('查看详情','javascript:void(0);',['class'=>'view_list','software_number'=>$searchModel->software_number,'shop_name'=>$shop_name]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $('.view_list').click(function () {
        var software_number = $(this).attr('software_number');
        var shop_name = $(this).attr('shop_name');
        var pageup = layer.open({
            type: 2,
            title: '开机时长列表',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/shop/shop-abnormal/view-list'])?>&software_number='+software_number+'&shop_name='+shop_name
        });
    })
</script>
