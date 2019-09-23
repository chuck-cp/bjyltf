<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
cms\assets\AppAsset::register($this);

$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
    <div class="sign-team-setting">
        <?php $form = ActiveForm::begin([
//            'action' => ['index'],
            'method' => 'post',
        ]); ?>
        <table class="table table-hover" >
            <?= $form->field($model,'id')->hiddenInput(['value'=>$team_id])->label(false)?>
            <tr>
                <td style="width: 150px;">首次签到时间:</td>
                <td colspan="">
                    <?= $form->field($model,'first_sign_time')->textInput([])->label(false)?>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">签到间隔时间:(分钟)</td>
                <td colspan="">
                    <?= $form->field($model,'sign_interval_time')->textInput([])->label(false)?>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">每人每日签到次数:(次)</td>
                <td colspan="">
                    <?= $form->field($model,'sign_qualified_number')->textInput([])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">默认最早下班时间:</td>
                <td colspan="">
                    <?= $form->field($model,'earliest_closing_time')->textInput([])->label(false);?>
                </td>
            </tr>

            <tr style="text-align: center;">
                <td colspan=""><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>
    </div>
<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>