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
            <tr>
                <td style="width: 95px;">*广告位名称:</td>
                <td colspan="3">
                    <?= $form->field($model,'name')->textInput([])->label(false)?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告位标识:</td>
                <td colspan="3">
                    <?= $form->field($model,'key')->textInput([])->label(false)?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告形式:</td>
                <td colspan="3">
                    <?= $form->field($model,'type')->dropDownList(AdvertConfig::getAdvertType(1),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告时长:</td>
                <td colspan="3" class="time">
                    <?= $form->field($model,'time')->checkboxList(AdvertConfig::getAdvertTime(3,$model->type),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告尺寸:</td>
                <td colspan="3" class="spec">
                    <?= $form->field($model,'spec')->checkboxList(AdvertConfig::getAdvertTime(4,$model->type),[])->label(false);?>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*广告频次:</td>
                <td>
                    <?= $form->field($model,'rate')->textInput([])->label(false)?>
                </td>
                <td style="width: 95px;">*频次倍数:</td>
                <td>
                    <select id="advertconfigsearch-shape" class="form-control" name="beishu">
                        <option value="1">1倍</option>
                        <option value="2">2倍</option>
                        <option value="3">3倍</option>
                        <option value="4">4倍</option>
                        <option value="5">5倍</option>
                        <option value="6">6倍</option>
                        <option value="7">7倍</option>
                        <option value="8">8倍</option>
                        <option value="9">9倍</option>
                        <option value="10">10倍</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="width: 95px;">*文件大小:</td>
                <td colspan="3">
                    <?= $form->field($model,'size')->textInput([])->label(false)?>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td colspan="4"><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
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
        var name = $('input[name="AdvertPositionSearch[name]"]').val();
//        var type = $('#advertpositionsearch-type').val();
        var rate = $('input[name="AdvertPositionSearch[rate]"]').val();
        var size = $('input[name="AdvertPositionSearch[size]"]').val();
        var time = $(".time input[type='checkbox']").is(':checked');
        var spec = $(".spec input[type='checkbox']").is(':checked');
        if(name == ''||rate == ''||size == ''||time == false ||spec== false ){
            layer.msg('请填写相关内容！');
            return false;
        }
    })
    $("#advertpositionsearch-type").change(function() {
        var typeid = $("#advertpositionsearch-type option:checked").val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['ajaxtype'])?>',
            type : 'POST',
            ContentType: "application/json; charset=utf-8",
            dataType : 'json',
            data : {'typeid':typeid},
            success:function(phpdata){
                $('#advertpositionsearch-time').empty();
                $('#advertpositionsearch-spec').empty();
//                phpdata.shape == 1?a="视频" : a="图片";
//                $('#advertpositionsearch-type').append('<option value='+phpdata.shape+'>'+a+'</option>');
                $.each(phpdata.time,function (i,item) {
                    $('#advertpositionsearch-time').append('<div class="checkbox"><label><input name="AdvertPositionSearch[time][]" value="'+i+'" type="checkbox">'+item+'</label></div>');
                })
                $.each(phpdata.spec,function (i,item) {
                    $('#advertpositionsearch-spec').append('<div class="checkbox"><label><input name="AdvertPositionSearch[spec][]" value="'+i+'" type="checkbox">'+item+'</label></div>');
                })
            },
            error:function(){
//                layer.msg('操作失败！');
            }
        })
    })
</script>
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