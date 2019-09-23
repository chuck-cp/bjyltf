<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopFloor */

$this->title = 'Create Building Shop Floor';
$this->params['breadcrumbs'][] = ['label' => 'Building Shop Floors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-shop-floor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
