<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDeviceFrame */

$this->title = 'Update System Device Frame: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'System Device Frames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-device-frame-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
