<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\modules\sign\models\SignTeam */

$this->title = 'Create Sign Team';
$this->params['breadcrumbs'][] = ['label' => 'Sign Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sign-team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
