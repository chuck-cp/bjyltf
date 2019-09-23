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
        'action' => ['bound-update'],
        'method' => 'post',
    ]);     ?>
    <table class="table table-hover" >
        <input type="hidden" name="userid" value="<?=Html::encode($userid)?>"/>
        <tr>
            <th style="width: 15px;"></th>
            <th style="width: 120px;">角色名</th>
            <th>角色描述</th>
        </tr>
        <? foreach($rulearray as $key=>$value):?>
        <tr class="item">
            <td><input type="checkbox" class="sy_dp_inp" name="item[]" value="<?=Html::encode($value['name']) ?>" <?if(in_array($value['name'],$itemname)):?>checked="true"<?endif;?> /></td>
            <td ><?=Html::encode($value['name']) ?></td>
            <td><?=Html::encode($value['description']) ?></td>
        </tr>
        <? endforeach; ?>
        <tr style="text-align: center;">
            <td colspan="3"><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
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
