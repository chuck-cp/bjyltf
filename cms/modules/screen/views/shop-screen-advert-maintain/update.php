<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\screen\models\ShopScreenAdvertMaintain */

$this->title = 'Update Shop Screen Advert Maintain: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shop Screen Advert Maintains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-screen-advert-maintain-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
