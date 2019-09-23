<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\search\ShopSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>


    <?php // echo $form->field($model, 'admin_member_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'area_name') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'apply_screen_number') ?>

    <?php // echo $form->field($model, 'screen_number') ?>

    <?php // echo $form->field($model, 'contact_name') ?>

    <?php // echo $form->field($model, 'contact_telephone') ?>

    <?php // echo $form->field($model, 'status') ?>


    <?php // echo $form->field($model, 'screen_status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'acreage') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
