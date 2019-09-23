<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '播放列表';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $(function(){
         $('.view_this').bind('click', function () {
            var id = $(this).attr('id');         
            layer.open({
                type: 2,
                title: '订单详情：',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '50%'],
                content: '".\yii\helpers\Url::to(['detail'])."&id='+id
            });
        })
        $('.view_report_this').bind('click', function () {
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                title: '报告信息：',
                shadeClose: true,
                shade: 0.8,
                area: ['80%', '80%'],
                content: '".\yii\helpers\Url::to(['reportlist'])."&id='+id
            });
        })
    })
");
?>
<div class="order-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/
            'id',
            'order_code',
            'member_name',
            'area_name',
            //'advert_id',
            'advert_name',
            'advert_time',
            'rate',
            //购买天数
            [
                'label' => '购买天数',
                'value' => function($searchModel){
                    return $searchModel->total_day;
                }
            ],
            //已播放天数
            [
                'label' => '已播放天数',
                'value' => function($searchModel){
                    if($searchModel->examine_status == 4){
                        return ToolsClass::timediff(strtotime($searchModel->orderDate['start_at']), time(), 'day');
                    }
                    return $searchModel->total_day;
                }
            ],
            //投放日期
            [
                'label' => '投放日期',
                'value' => function($searchModel){
                    return $searchModel->orderDate['start_at'];
                }
            ],
            //播放完成日期
            [
                'label' => '完成日期',
                'value' => function($searchModel){
                    return $searchModel->orderDate['end_at'];
                }
            ],
            'screen_number',
            [
                'label' => '投放状态',
                'value' => function($searchModel){
                    return $searchModel->examine_status == 4 ? '播放中' : '已完成';
                }
            ],

            [
                    'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{order_view} {view} {reportlist} {front-report}',
                'buttons' => [
                    'order_view' => function($url,$searchModel){
                        return Html::a('订单详情', ['order-view','id'=>$searchModel->id], ['id'=>$searchModel->id,'class'=>'order_view']);
                    },
                    'view' => function($url,$searchModel){
                        return Html::a('查看', 'javascript:void(0);', ['id'=>$searchModel->id,'class'=>'view_this']);
                    },

                    'reportlist' => function ($url, $searchModel)
                    {
                        {
                            return Html::a('报告', 'javascript:void(0);', ['id' => $searchModel->id, 'class' => 'view_report_this']);
                        }
                    },
                    'front-report' => function($url,$searchModel){
//                        if($searchModel->examine_status == 5){
                            return Html::a('前台播放报告', ['front-report','id'=>$searchModel->id], ['class'=>'front_view_', 'target'=>'_blank']);
//                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
