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
        <? if($type==1): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>1])->label(false)?>
            <tr>
                <td style="width: 95px;">*广告形式:</td><td><?= $form->field($model,'shape')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($type==2): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>2])->label(false)?>
            <tr>
                <td style="width: 95px;">*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告格式:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($type==3): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>3])->label(false)?>
            <tr>
                <td style="width: 95px;">*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告时长:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? elseif($type==4): ?>
            <?= $form->field($model,'type')->hiddenInput(['value'=>4])->label(false)?>
            <tr>
                <td style="width: 95px;">*广告形式:</td>
                <td>
                    <?= $form->field($model,'shape')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告尺寸:</td><td><?= $form->field($model,'content')->textInput([])->label(false)?></td>
            </tr>
        <? endif;?>
            <tr>
                <td colspan="2"><?= Html::Button('提交',['class'=>'btn btn-primary'])?></td>
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
    $('.btn-primary').click(function(){
        var type = $('#advertconfigsearch-type').val();
        var shape = $('#advertconfigsearch-shape').val();
        var content = $('#advertconfigsearch-content').val();
        if(content == ''){
            layer.msg('请填写参数内容！');
            return false;
        }else{
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['checksame'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'type':type,'shape':shape,'content':content},
                success:function (resdata) {
                    if(resdata !=1){
                        layer.msg('已存在参数，请勿重复设置！');
                        return false;
//                        setTimeout(function(){
//                            window.parent.location.reload();
//                        },1000);
                    }else{
                        $('#w0').submit();
                    }
                },error:function (error) {
//                    layer.msg('操作失败！');
                }
            });
        }
    })
</script>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{display: inline-block;}
    .detail:hover{cursor:pointer;}
    #w0{display: flex;justify-content:center;align-items:center; width:100%;}
    table th,table td{text-align: center; }
</style>
