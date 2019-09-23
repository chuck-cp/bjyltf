<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopHeadquarters */

$this->title = 'Create Shop Headquarters';
$this->params['breadcrumbs'][] = ['label' => 'Shop Headquarters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-headquarters-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
