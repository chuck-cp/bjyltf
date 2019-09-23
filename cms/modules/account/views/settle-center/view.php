<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\LogPayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Log Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-payment-view">

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
            'serial_number',
            'order_code',
            'price',
            'pay_type',
            'pay_status',
            'payment_code',
            'other_account',
            'other_serial',
            'other_note:ntext',
            'pay_at',
        ],
    ]) ?>

</div>
