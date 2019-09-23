<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemBanner */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Banner管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = '查看';
?>
<div class="system-banner-view">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'image_url:url',
            [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'width'=>'200',
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
            'sort',
        ],
    ]) ?>

</div>
