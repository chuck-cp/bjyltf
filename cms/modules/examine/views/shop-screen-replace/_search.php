<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\search\ShopScreenReplaceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-screen-replace-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'shop_id') ?>

    <?= $form->field($model, 'shop_name') ?>

    <?= $form->field($model, 'shop_area_id') ?>

    <?= $form->field($model, 'shop_address') ?>

    <?php // echo $form->field($model, 'install_member_id') ?>

    <?php // echo $form->field($model, 'install_member_name') ?>

    <?php // echo $form->field($model, 'install_finish_at') ?>

    <?php // echo $form->field($model, 'install_price') ?>

    <?php // echo $form->field($model, 'replace_screen_number') ?>

    <?php // echo $form->field($model, 'create_user_id') ?>

    <?php // echo $form->field($model, 'create_user_name') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'assign_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
