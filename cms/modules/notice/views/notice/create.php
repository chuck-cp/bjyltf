<?php

use yii\helpers\Html;
use \kucha\ueditor\UEditor;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemNotice */

$this->title = '发布系统公告';
$this->params['breadcrumbs'][] = ['label' => '公告管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-notice-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
