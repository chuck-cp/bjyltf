<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MemberSearchTeamList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-team-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index','mobile'=>$mobile],
        'method' => 'get',
    ]); ?>
    <table class="table table-bordered">
        <input type="hidden" name="team_id" value="<?=$member_id?>">
        <tr>
            <td>
                <?= $form->field($model, 'member_name') ?>
            </td>
            <td>
                <?= $form->field($model, 'mobile') ?>
            </td>
            <td style="text-align: center;">
                <div class="form-group" style="margin-top: 25px;">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary','name'=>'search','value'=>1]) ?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-default','name'=>'search','value'=>0])?>
                </div>
            </td>
            <td></td>
        </tr>
    </table>


    <?php ActiveForm::end(); ?>

</div>
