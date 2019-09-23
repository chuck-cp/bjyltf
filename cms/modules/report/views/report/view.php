<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

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
            'member_id',
            'member_name',
            'salesman_id',
            'salesman_name',
            'salesman_mobile',
            'custom_member_id',
            'custom_service_name',
            'custom_service_mobile',
            'order_code',
            'order_price',
            'unit_price',
            'total_day',
            'payment_type',
            'payment_price',
            'payment_at',
            'overdue_at',
            'last_payment_at',
            'overdue_number',
            'screen_number',
            'rate',
            'area_name',
            'company_area_id',
            'advert_id',
            'advert_key',
            'advert_name',
            'advert_time',
            'resource',
            'resource_name',
            'resource_thumbnail',
            'download_number',
            'video_id',
            'create_at',
            'payment_status',
            'examine_status',
            'lock',
        ],
    ]) ?>

</div>
