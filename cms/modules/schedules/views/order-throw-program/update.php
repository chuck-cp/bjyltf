<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\OrderThrowProgram */

$this->title = 'Update Order Throw Program: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Order Throw Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-throw-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
