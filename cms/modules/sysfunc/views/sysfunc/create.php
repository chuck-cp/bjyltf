<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sysfunc\models\SystemFunction */

$this->title = '创建模块';
$this->params['breadcrumbs'][] = ['label' => '模块管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-function-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
