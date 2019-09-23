<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopScreenReplace */

$this->title = 'Update Shop Screen Replace: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Shop Screen Replaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-screen-replace-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
