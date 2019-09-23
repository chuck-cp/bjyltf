<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeamList */

$this->title = 'Update Member Team List: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Member Team Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->team_member_id, 'url' => ['view', 'id' => $model->team_member_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="member-team-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
