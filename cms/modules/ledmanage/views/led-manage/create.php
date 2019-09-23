<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDevice */

$this->title = '添加设备';
$this->params['breadcrumbs'][] = ['label' => 'LED库存管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<div class="system-device-create">
    <?php echo $this->render('layout/add_device',['kuid'=>$kuid]);?>
    <?= $this->render('_form_sec', ['kuid' => $kuid,'model'=>$model]) ?>
</div>
