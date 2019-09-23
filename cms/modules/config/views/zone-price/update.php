<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemZonePrice */

$this->title = 'Update System Zone Price: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'System Zone Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->area_id, 'url' => ['view', 'id' => $model->area_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-zone-price-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
