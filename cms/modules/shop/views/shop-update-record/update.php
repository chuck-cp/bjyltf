<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\ShopUpdateRecord */

$this->title = 'Update Shop Update Record: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Update Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-update-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
