<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\search\ShopContractSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-contract-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'shop_id') ?>

    <?= $form->field($model, 'shop_type') ?>

    <?= $form->field($model, 'contract_number') ?>

    <?= $form->field($model, 'cabinet_number') ?>

    <?php // echo $form->field($model, 'receiver_name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'examine_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
