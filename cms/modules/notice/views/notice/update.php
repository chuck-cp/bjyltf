<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemNotice */

$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = '修改公告';
?>
<div class="system-notice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
