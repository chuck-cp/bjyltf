<?php

use yii\helpers\Html;

$this->title = '添加画框设备';
$this->params['breadcrumbs'][] = ['label' => '库存管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<div class="system-device-frame-create">
    <?php echo $this->render('layout/add_frame',['kuid'=>$kuid]);?>
    <?= $this->render('_form_sec', ['kuid' => $kuid,'model'=>$model]) ?>
</div>