<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\withdraw\models\search\MemberWithdrawSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-withdraw-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<!--    --><?//= $form->field($model, 'id') ?>
<!---->
<!--    --><?//= $form->field($model, 'serial_number') ?>
<!---->
<!--    --><?//= $form->field($model, 'member_id') ?>
<!---->
<!--    --><?//= $form->field($model, 'member_name') ?>
<!---->
<!--    --><?//= $form->field($model, 'member_number') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'back_name') ?>

    <?php // echo $form->field($model, 'back_mobile') ?>

    <?php // echo $form->field($model, 'payee_name') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'poundage') ?>

    <?php // echo $form->field($model, 'account_balance') ?>

    <?php // echo $form->field($model, 'examine_statis') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'account_type') ?>
    <div class="row">
        <div class="col-xs-2 form-control">
            <?= $form->field($model, 'serial_number') ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
