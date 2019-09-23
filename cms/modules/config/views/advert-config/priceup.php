<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use \cms\models\AdvertPosition;
use cms\modules\config\models\AdvertConfig;
use \common\libs\ToolsClass;

\cms\assets\AppAsset::register($this);
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
<div class="member-search">
    <?php $form = ActiveForm::begin([
//        'action' => [''],
        'method' => 'post',
    ]);     ?>
    <table class="table table-hover" >
        <?= $form->field($model,'id')->hiddenInput(['id'=>$model->id])->label(false)?>
        <?= $form->field($model,'create_user_id')->hiddenInput(['value'=>Yii::$app->user->identity->getId()])->label(false)?>
        <?= $form->field($model,'create_user_name')->hiddenInput(['value'=>Yii::$app->user->identity->username])->label(false)?>
        <tr>
            <td style="width: 115px;">*广告位名称:</td>
            <td colspan="">
                <?=Html::encode(AdvertPosition::getAdvertPlace($model->advert_id)[$model->advert_id])?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*广告形式:</td>
            <td colspan="">
                <?=Html::encode($model->type==1?'视频':'图片')?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*广告时长:</td>
            <td colspan="">
                <?=Html::encode($model->time)?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*一级广告价格:</td>
            <td>
                <?= $form->field($model,'price_1')->textInput(['value'=>$model->price_1/100])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*二级广告价格:</td>
            <td>
                <?= $form->field($model,'price_2')->textInput(['value'=>$model->price_2/100])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*三级广告价格:</td>
            <td>
                <?= $form->field($model,'price_3')->textInput(['value'=>$model->price_3/100])->label(false);?>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="2"><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    .radio, .checkbox {
        display: inline-block;
        min-height: 20px;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-left: 20px;
        width: 100px;
        vertical-align: bottom;
    }
</style>
