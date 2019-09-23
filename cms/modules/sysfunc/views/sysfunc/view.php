<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sysfunc\models\SystemFunction */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '模块列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>
<div class="system-function-view">

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这个模块吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
           // 'image_url:url',
            [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'width'=>'50',
                        'height'=>'auto'
                    ]
                ],
                'value' => function ($model) {
                    if(!$model->image_url){
                        return '暂无图片';
                    }
                    return $model->image_url;
                }
            ],
            'link_url:url',
            'target',
           // 'status',
            [
                'label' => '状态',
                'value' => function($model){
                    if($model->status == 1){
                        return '开启';
                    }elseif($model->status == 2){
                        return '关闭';
                    }elseif($model->status == 3){
                        return '加密';
                    }
                }
            ],
        ],
    ]) ?>

</div>
