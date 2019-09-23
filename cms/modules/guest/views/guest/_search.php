<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\search\ShopkfSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'headquarters_id') ?>

    <?= $form->field($model, 'headquarters_list_id') ?>

    <?= $form->field($model, 'activity_detail_id') ?>

    <?php // echo $form->field($model, 'introducer_member_id') ?>

    <?php // echo $form->field($model, 'introducer_member_name') ?>

    <?php // echo $form->field($model, 'introducer_member_mobile') ?>

    <?php // echo $form->field($model, 'introducer_member_price') ?>

    <?php // echo $form->field($model, 'member_name') ?>

    <?php // echo $form->field($model, 'member_mobile') ?>

    <?php // echo $form->field($model, 'member_price') ?>

    <?php // echo $form->field($model, 'member_inside') ?>

    <?php // echo $form->field($model, 'member_reward_price') ?>

    <?php // echo $form->field($model, 'parent_member_id') ?>

    <?php // echo $form->field($model, 'parent_member_price') ?>

    <?php // echo $form->field($model, 'admin_member_id') ?>

    <?php // echo $form->field($model, 'shop_member_id') ?>

    <?php // echo $form->field($model, 'wx_member_id') ?>

    <?php // echo $form->field($model, 'shop_image') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'area_name') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'apply_screen_number') ?>

    <?php // echo $form->field($model, 'screen_number') ?>

    <?php // echo $form->field($model, 'error_screen_number') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'screen_status') ?>

    <?php // echo $form->field($model, 'install_status') ?>

    <?php // echo $form->field($model, 'delivery_status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'acreage') ?>

    <?php // echo $form->field($model, 'apply_client') ?>

    <?php // echo $form->field($model, 'mirror_account') ?>

    <?php // echo $form->field($model, 'shop_type') ?>

    <?php // echo $form->field($model, 'shop_operate_type') ?>

    <?php // echo $form->field($model, 'install_team_id') ?>

    <?php // echo $form->field($model, 'install_member_id') ?>

    <?php // echo $form->field($model, 'install_member_name') ?>

    <?php // echo $form->field($model, 'install_mobile') ?>

    <?php // echo $form->field($model, 'install_price') ?>

    <?php // echo $form->field($model, 'install_finish_at') ?>

    <?php // echo $form->field($model, 'last_examine_user_id') ?>

    <?php // echo $form->field($model, 'examine_user_group') ?>

    <?php // echo $form->field($model, 'examine_user_name') ?>

    <?php // echo $form->field($model, 'examine_number') ?>

    <?php // echo $form->field($model, 'agreement_name') ?>

    <?php // echo $form->field($model, 'replace_screen_status') ?>

    <?php // echo $form->field($model, 'install_assign_at') ?>

    <?php // echo $form->field($model, 'install_assign_time') ?>

    <?php // echo $form->field($model, 'shop_examine_at') ?>

    <?php // echo $form->field($model, 'agreed') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'bd_longitude') ?>

    <?php // echo $form->field($model, 'bd_latitude') ?>

    <?php // echo $form->field($model, 'repeat_mobile') ?>

    <?php // echo $form->field($model, 'repeat_company_name') ?>

    <?php // echo $form->field($model, 'lable_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
