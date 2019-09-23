<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\feedback\models\OrderComplain */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Order Complains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-complain-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'order_id',
            'member_id',
            'complain_member_id',
            'complain_member_name',
            'complain_type',
            'complain_level',
            'complain_content',
            'create_at',
        ],
    ]) ?>

</div>
