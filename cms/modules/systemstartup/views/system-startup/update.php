<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\modules\systemstartup\models\SystemStartup */

$this->title = '编辑: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '启动页管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="system-startup-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
