<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
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
        <tr>
            <td style="width: 95px;">*权限名称:</td>
        <tr>
        </tr>
            <td><?= $form->field($model,'name')->textInput([])->label(false)?></td>
        </tr>
        <tr>
            <td style="width: 95px;">*权限描述:</td>
        <tr>
        </tr>
            <td><?= $form->field($model,'data')->textInput([])->label(false);?></td>
        </tr>
        <tr>
            <td style="width: 95px;">*权限等级:</td>
        <tr>
        </tr>
        <td>
            <select class="form-control" name="AuthRule[level]">
                <option value="1" <? if($model->level == 1):?>selected<? endif; ?>>模块</option>
                <option value="2" <? if($model->level == 2):?>selected<? endif; ?>>模块/控制器</option>
                <option value="3" <? if($model->level == 3):?>selected<? endif; ?>>模块/控制器/方法</option>
            </select>
        </td>
        </tr>
        <tr style="text-align: center;">
            <td><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
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
