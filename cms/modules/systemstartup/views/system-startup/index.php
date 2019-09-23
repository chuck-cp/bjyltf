<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\systemstartup\models\search\SystemStartupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '启动页管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row" style="padding-left: 15px; padding-bottom: 15px;">
    <?=Html::a('新建启动页',['create'],['class'=>'btn btn-success'])?>
</div>
<div class="system-startup-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
            'id',
          //  'version',
            [
                'attribute' => 'type',
                'label'=>'类型',
                'value'=>
                    function($searchModel){
                        return $searchModel->type==1?"活动启动页":"开屏广告";
                    },
            ],
            [
                'attribute' => 'visibility',
                'label'=>'可见次数',
                'value'=>
                    function($searchModel){
                        return $searchModel->visibility==1?"每次可见":"首次可见";
                    },
            ],

        ],
    ]); ?>
</div>
<style type="text/css">
    .table th, .table td {
        text-align: center;
        vertical-align: middle!important;
    }
</style>