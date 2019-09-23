<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemBanner */

$this->title = '修改banner';
$this->params['breadcrumbs'][] = ['label' => 'Banner管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="system-banner-update">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
