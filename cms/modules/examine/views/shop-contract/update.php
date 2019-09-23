<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopContract */

$this->title = 'Update Shop Contract: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-contract-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
