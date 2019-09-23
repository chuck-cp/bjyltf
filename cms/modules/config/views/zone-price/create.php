<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemZonePrice */

$this->title = '创建店铺价格';
$this->params['breadcrumbs'][] = ['label' => '系统区域价格', 'url' => ['zone']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-zone-price-create">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
<!--    --><?php //echo $this->render('layout/tab')?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
