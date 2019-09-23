<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sysfunc\models\SystemFunction */

$this->title = '模块修改';
$this->params['breadcrumbs'][] = ['label' => '模块管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="system-function-update">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
