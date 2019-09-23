<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\withdraw\models\MemberWithdraw */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Member Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-withdraw-view">

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
            'member_id',
            'member_name',
            'mobile',
            'back_name',
            'back_mobile',
            'payee_name',
            'status',
            'price',
            'poundage',
            'account_balance',
            'examine_statis',
            'create_at',
            'account_type',
        ],
    ]) ?>

</div>
