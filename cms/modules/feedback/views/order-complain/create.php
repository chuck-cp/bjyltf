<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\feedback\models\OrderComplain */

$this->title = 'Create Order Complain';
$this->params['breadcrumbs'][] = ['label' => 'Order Complains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-complain-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
