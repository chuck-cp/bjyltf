<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemVersion */

$this->title = '新建版本';
$this->params['breadcrumbs'][] = ['label' => '系统版本', 'url' => ['version']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-version-create">
    <div class="row col-md-4">
        <?= $this->render('_form', [
            'model' => $model,
            'app_type' => $app_type,
        ]) ?>
    </div>


</div>
