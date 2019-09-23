<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopHeadquarters */

$this->title = 'Update Shop Headquarters: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Headquarters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-headquarters-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
