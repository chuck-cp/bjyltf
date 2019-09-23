<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '版本管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'IOS';
$action = Yii::$app->controller->action->id;
$this->registerJs("
    $(function(){             
       $('.has-sub').eq(2).addClass('active');

    })
");
?>
<div class="system-version-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('layout/tab')?>
        <p>
            <?= Html::a('发布新版本', ['create','app_type'=>2], ['class' => 'btn btn-success']) ?>
        </p>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'id',
            //'app_type',
            [
                'label' => '应用类型',
                'value' => function($model){
                    return $model->app_type == 1 ? '安卓' : 'IOS';
                }
            ],
            'create_at',
            //'upgrade_type',
            [
                'label' => '是否强制升级',
                'value' => function($model){
                    return $model->upgrade_type == 1 ? '强制升级' : '不强制';
                }
            ],
            'version',
            [
                'label' => '状态',
                'value' => function($model){
                    return $model->status == 1 ? '正常' : '停运';
                }
            ],
            'create_user',
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {stop}',
                'buttons' => [
                    'view' => function($url,$model){
                        return html::tag('span','查看',['class'=>'detail','id'=>$model->id]);
                    },
                    'stop' => function($url,$model){
                        return $model->status == 1 ? html::tag('span','停用',['class'=>'stop','id'=>$model->id]) : '';
                    }
                ],
            ],
        ],
    ]); ?>
</div>
