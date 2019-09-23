<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MembeTeamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-team-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <table class="grid table table-striped table-bordered search">
        <tr>
            <td>
                <?= $form->field($model, 'team_name') ?>
            </td>
            <td>
                <?= $form->field($model, 'team_member_name') ?>
            </td>

            <td>
                <?= $form->field($model, 'phone') ?>
            </td>
            <td class="">
                <div class="form-group left-middle" style="margin-top: 25px;">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary','name'=>'search','value'=>1]) ?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-default','name'=>'search','value'=>0])?>
                </div>
            </td>
        </tr>
    </table>










    <?php // echo $form->field($model, 'live_address') ?>

    <?php // echo $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'company_area_name') ?>

    <?php // echo $form->field($model, 'company_area_id') ?>

    <?php // echo $form->field($model, 'company_address') ?>

    <?php // echo $form->field($model, 'install_shop_number') ?>

    <?php // echo $form->field($model, 'not_install_shop_number') ?>

    <?php // echo $form->field($model, 'not_assign_shop_number') ?>


    <?php ActiveForm::end(); ?>

</div>
