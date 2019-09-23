<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ArrayClass;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\notice\models\search\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公告管理';
$this->params['breadcrumbs'][] = $this->title;
$msg = Yii::$app->request->get('msg');
?>
<div class="system-notice-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => '标题',
                'value' => function($model){
                    return ArrayClass::truncate_utf8_string($model->title,15);
                }
            ],
            'create_at',
            'create_user',
            //'content:ntext',
            //'top',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('修改',['update','id'=>$model->id]);
                    },
                    'update' => function($url,$model){
                        return html::a('查看','javascript:void(0);',['class'=>'views','id'=>$model->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 115px;display: inline-block;}
    .detail:hover{cursor:pointer;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $('.views').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '详情',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/notice/notice/view'])?>&id='+id
        });
    })
</script>