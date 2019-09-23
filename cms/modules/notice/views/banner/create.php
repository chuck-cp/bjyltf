<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemBanner */

$this->title = '发布新Banner';
$this->params['breadcrumbs'][] = ['label' => 'Banner管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-banner-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
