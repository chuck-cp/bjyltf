<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberInstallSubsidy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Member Install Subsidies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-install-subsidy-view">

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
            'install_member_id',
            'install_shop_number',
            'install_screen_number',
            'assign_shop_number',
            'assign_screen_number',
            'income_price',
            'subsidy_price',
            'create_at',
        ],
    ]) ?>

</div>
