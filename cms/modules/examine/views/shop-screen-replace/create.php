<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\ShopScreenReplace */

$this->title = 'Create Shop Screen Replace';
$this->params['breadcrumbs'][] = ['label' => 'Shop Screen Replaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-replace-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
