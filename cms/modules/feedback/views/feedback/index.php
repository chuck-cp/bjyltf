<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\feedback\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '意见栏';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('
    $(".detail").bind("click",function(){
        var question = $(this).attr("question");
        var content = $(this).attr("content");
        layer.open({
          type: 1,
          skin: \'layui-layer-rim\', //加上边框
          area: [\'480px\', \'auto\'], //宽高
          content: "<div class=\"big\"><div class=\"one\"><h5>遇见的问题：</h5><p>"+question+"</p></div><div class=\"two\"><h5>提出的意见：</h5><p>"+content+"</p></div></div>",
        });
    })
');
?>


<div class="feedback-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/
            'id',
//            'member_id',
//            'question',
//            'content',
//            'create_at',
            [
                'label' => '工号',
                'value' =>function($model){
                    return $model->member_id;
                }
            ],
            [
                'label' => '姓名',
                'value' =>function($models){
                    return $models->member['attributes']['name'];
                }
            ],
            [
                'label' => '联系电话',
                'value' =>function($models){
                    return $models->member['mobile'];
                }
            ],
            [
                'label' => '问题描述',
                'value' =>function($model){
                    return \common\libs\ArrayClass::truncate_utf8_string($model->question,15);
                }
            ],
            [
                'label' => '提交日期',
                'value' =>function($model){
                    return $model->create_at;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'header'=>'操作',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::tag('span','查看详情',['class'=>'detail','content'=>$model->content,'question'=>$model->question]);
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