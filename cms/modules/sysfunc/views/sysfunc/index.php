<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sysfunc\models\search\FunctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模块管理';
$this->params['breadcrumbs'][] = '模块管理';
?>
<div class="row" style="padding-left: 15px; padding-bottom: 15px;">
    <?=Html::a('创建模块',['create'],['class'=>'btn btn-success'])?>
</div>
<div class="system-function-index">

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',

            [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'width'=>'50',
                        'height'=>'auto'
                    ]
                ],
                'value' => function ($searchModel) {
                    return $searchModel->image_url;
                }
            ],
            'link_url:url',
            'target',
            [
                'label' => '状态',
                'value' => function($model){
                    if($model->status == 1){
                        return '开启';
                    }elseif($model->status == 2){
                        return '关闭';
                    }elseif($model->status == 3){
                        return '内部管理员可见';
                    }elseif($model->status == 4){
                        return '签到管理员可见';
                    }
                }
            ],
            //['class' => 'yii\grid\ActionColumn',]
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {dels}',
                'buttons' => [
//                    'view' => function($url,$model){
//                        return Html::a('查看',$url,['view','id'=>$model->id]);
//                    },
                    'update' => function($url,$model){
                        return Html::a('修改',$url,['update','id'=>$model->id]);
                    },
//                    'dels' =>function($url,$model){
//                        return Html::a('删除', ['delete', 'id' => $model->id], [
//                            'data' => [
//                                'confirm' => '您确定要删除这个模块吗?',
//                                'method' => 'post',
//                            ],
//                        ]);
//                    }
                ],
            ],
        ],
    ]); ?>
</div>
