<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeamList */

$this->title = 'Create Member Team List';
$this->params['breadcrumbs'][] = ['label' => 'Member Team Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-team-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
