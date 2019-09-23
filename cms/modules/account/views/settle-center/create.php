<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\account\models\LogPayment */

$this->title = 'Create Log Payment';
$this->params['breadcrumbs'][] = ['label' => 'Log Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
