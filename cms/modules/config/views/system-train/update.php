<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemTrain */

$this->title = 'Update System Train: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'System Trains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-train-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
