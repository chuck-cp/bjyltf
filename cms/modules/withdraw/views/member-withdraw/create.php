<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\withdraw\models\MemberWithdraw */

$this->title = 'Create Member Withdraw';
$this->params['breadcrumbs'][] = ['label' => 'Member Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-withdraw-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
