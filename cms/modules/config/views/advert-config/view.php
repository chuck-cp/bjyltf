<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\AdvertConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advert Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-config-view">

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
            'shape',
            'content',
            'type',
            'update_at',
            'create_user_id',
            'create_user_name',
        ],
    ]) ?>

</div>
