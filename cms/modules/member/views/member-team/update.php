<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeam */

$this->title = 'Update Member Team: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Member Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->member_id, 'url' => ['view', 'id' => $model->member_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="member-team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
