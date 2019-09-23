<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopFloor */

$this->title = 'Update Building Shop Floor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Building Shop Floors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="building-shop-floor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
