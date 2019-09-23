<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\ShopUpdateRecord */

$this->title = 'Create Shop Update Record';
$this->params['breadcrumbs'][] = ['label' => 'Shop Update Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-update-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
