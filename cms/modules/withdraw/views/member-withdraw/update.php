<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\withdraw\models\MemberWithdraw */

$this->title = 'Update Member Withdraw: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Member Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="member-withdraw-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
