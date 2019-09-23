<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\MemberTeam */

$this->title = 'Create Member Team';
$this->params['breadcrumbs'][] = ['label' => 'Member Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
