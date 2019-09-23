<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemVersion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'System Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-version-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'app_type',
            'upgrade_type',
            'version',
            'url:url',
            'desc',
            'create_at',
        ],
    ]) ?>

</div>
