<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopPark */

$this->title = 'Update Building Shop Park: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Building Shop Parks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="building-shop-park-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
