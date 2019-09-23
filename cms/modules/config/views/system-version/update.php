<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemVersion */

$this->title = 'Update System Version: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'System Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-version-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'app_type' => $model->app_type,
    ]) ?>

</div>
