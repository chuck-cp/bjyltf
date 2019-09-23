<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\screen\models\ShopScreenAdvertMaintain */

$this->title = 'Create Shop Screen Advert Maintain';
$this->params['breadcrumbs'][] = ['label' => 'Shop Screen Advert Maintains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-advert-maintain-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
