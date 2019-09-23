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
<div class="search">
    <?php $form = ActiveForm::begin([
//        'action' => [''],
        'method' => 'post',
    ]);     ?>
    <table class="table table-hover" >
        <tr>
            <td style="width: 95px;">*角色名:</td>
        <tr>
        </tr>
            <td><?= $form->field($model,'name')->textInput([])->label(false)?></td>
        </tr>
        <?= $form->field($model,'type')->hiddenInput(['value'=>1])->label(false)?></td>
        <tr>
            <td style="width: 95px;">*角色描述:</td>
        <tr>
        </tr>
            <td><?= $form->field($model,'description')->textInput([])->label(false);?></td>
        </tr>
        <tr style="text-align: center;">
            <td colspan=""><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
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

</style>
