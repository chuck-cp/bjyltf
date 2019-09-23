<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\feedback\models\OrderComplain */

$this->title = 'Update Order Complain: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Order Complains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-complain-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
