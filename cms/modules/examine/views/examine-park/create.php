<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\BuildingShopPark */

$this->title = 'Create Building Shop Park';
$this->params['breadcrumbs'][] = ['label' => 'Building Shop Parks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-shop-park-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
