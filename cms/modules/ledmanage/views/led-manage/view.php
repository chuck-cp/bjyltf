<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDevice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'System Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-device-view">

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
            'device_number',
            'manufactor',
            'batch',
            'gps',
            'receiving_at',
            'remark',
            'is_output',
            'status',
            'create_at',
        ],
    ]) ?>

</div>
