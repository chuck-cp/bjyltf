<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;
use cms\modules\config\models\AdvertConfig;
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
        <?= $form->field($model,'create_user_id')->hiddenInput(['value'=>Yii::$app->user->identity->getId()])->label(false)?>
        <?= $form->field($model,'create_user_name')->hiddenInput(['value'=>Yii::$app->user->identity->username])->label(false)?>
        <? if($model->type==1): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>1])->label(false)?>
            <tr>
                <td>*广告形式:</td><td><?= $form->field($model,'shape')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($model->type==2): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>2])->label(false)?>
            <tr>
                <td>*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>*广告格式:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($model->type==3): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>3])->label(false)?>
            <tr>
                <td>*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>*广告时长:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($model->type==4): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>4])->label(false)?>
            <tr>
                <td>*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>*广告尺寸:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? endif;?>
            <tr>
                <td colspan="2"><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
            </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<!--<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">

</script>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{display: inline-block;}
    .detail:hover{cursor:pointer;}
    #w0{display: flex;justify-content:center;align-items:center; width:100%;}
    table th,table td{text-align: center; }
</style>
