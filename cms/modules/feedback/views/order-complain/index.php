<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\feedback\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '投诉查询';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('
    $(".detail").bind("click",function(){
        var complain_level = $(this).attr("complain_level");
        var name = $(this).attr("name");
        var mobile = $(this).attr("mobile");
        var complain_type = $(this).attr("complain_type");
        var complain_member_name = $(this).attr("complain_member_name");
        var complain_content = $(this).attr("complain_content");
        layer.open({
          type: 1,  
          skin: \'layui-layer-rim\', //加上边框
          area: [\'480px\', \'auto\'], //宽高
          content: "<div class=\"big\"><div class=\"one\"><h5>投诉等级："+complain_level+"</h5></div><div class=\"three\"><h5>投诉人信息：</h5></div><div class=\"three\"><h5>姓名："+name+"&nbsp;&nbsp;&nbsp;&nbsp;电话："+mobile+"</h5></div><div class=\"four\"><h5>被投诉人信息：</h5></div><div class=\"five\"><h5>职务："+complain_type+"&nbsp;&nbsp;&nbsp;&nbsp;姓名："+complain_member_name+"</h5></div><div class=\"two\"><h5>投诉内容：</h5></div><p>"+complain_content+"</p></div>",
        });
    })
');
?>
<div class="feedback-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '订单号',
                'value' => function($model){
                    return $model->orderInfo['order_code'];
                }
            ],
            //'member_id',
            //'complain_member_id',
            'complain_member_name',
            //'complain_type',
            [
                'label' => '投诉人手机号',
                'value' => function($model){
                    return $model->member['mobile'];
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($model){
                    return \cms\modules\feedback\models\OrderComplain::getComplainLevel($model->complain_level);
                }
            ],
            [
                'label' => '问题描述',
                'value' =>function($model){
                    return \common\libs\ArrayClass::truncate_utf8_string($model->complain_content,15);
                }
            ],
            'create_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'header'=>'操作',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::tag(
                                'span',
                                '查看',
                                [
                                    'class'=>'detail',
                                    //投诉等级
                                    'complain_level'=>$model->complain_level,
                                    //投诉人姓名
                                    'name'=>$model->member['attributes']['name'],
                                    //投诉人电话
                                    'mobile'=>$model->member['attributes']['mobile'],
                                    //被投诉人职务
                                    'complain_type'=>$model->complain_type==1?'广告对接人':'业务合作人',
                                    //被投诉人姓名
                                    'complain_member_name'=>$model->complain_member_name,
                                    //投诉内容
                                    'complain_content'=>$model->complain_content,
                                ]
                        );
                        return Html::tag('span','查看',['class'=>'detail']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .detail:hover{cursor:pointer;}
    .big{padding-left: 13px;padding-right: 13px;}
    .two{margin-top: 20px;padding-bottom: 20px;}
    h5{font-weight: 700;}
</style>
