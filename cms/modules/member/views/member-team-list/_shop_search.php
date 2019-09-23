<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MemberSearchTeamList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-team-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['record','mobile'=>$mobile],
        'method' => 'get',
    ]); ?>
    <table class="table table-bordered">
        <input type="hidden" name="team_id" value="<?=$member_id?>">
        <tr>
            <td>
                <?= $form->field($model, 'name') ?>
            </td>
            <td>
                <?= $form->field($model, 'install_member_name')->label('被指派人') ?>
            </td>
            <td>
                <?= $form->field($model, 'mobile')->label('被指派人联系方式') ?>
            </td>
            <td>
                <?=$form->field($model,'status')->dropDownList(['-10'=>'未安装','5'=>'已安装'],['prompt'=>'全部'])->label('安装状态')?>
            </td>
            <td style="text-align: center;">
                <div class="form-group" style="margin-top: 25px;">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary','name'=>'search','value'=>1]) ?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-default','name'=>'search','value'=>0])?>
                </div>
            </td>
        </tr>
    </table>
    <?php // echo $form->field($model, 'wait_shop_number') ?>


    <?php ActiveForm::end(); ?>

</div>