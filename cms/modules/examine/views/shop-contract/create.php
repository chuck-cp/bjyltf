<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopContract */

$this->title = 'Create Shop Contract';
$this->params['breadcrumbs'][] = ['label' => 'Shop Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-contract-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
