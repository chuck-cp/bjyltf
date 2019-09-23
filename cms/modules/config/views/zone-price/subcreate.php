<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemZonePrice */

$this->title = '创建补贴价格';
$this->params['breadcrumbs'][] = ['label' => '每日补贴价格', 'url' => ['subsidy']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-zone-price-create">
    <?php echo $this->render('layout/tab')?>
    <?= $this->render('_form_sub', [
        'model' => $model,
    ]) ?>

</div>
