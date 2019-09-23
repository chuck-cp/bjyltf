<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\OrderThrowProgram */

$this->title = 'Create Order Throw Program';
$this->params['breadcrumbs'][] = ['label' => 'Order Throw Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-throw-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
