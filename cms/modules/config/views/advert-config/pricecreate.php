<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use \cms\models\AdvertPosition;
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
            <td style="width: 115px;">*广告位名称:</td>
            <td>
                <?= $form->field($model,'advert_id')->dropDownList(AdvertPosition::getAllAdvertname(1),[])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*广告形式:</td>
            <td>
                <?= $form->field($model,'type')->dropDownList([$dataone['type']=>$dataone['type']==1?'视频':'图片'],[])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*广告时长:</td>
            <td>
                <?= $form->field($model,'time')->dropDownList($dataone['time'],[])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*一级广告价格:</td>
            <td>
                <?= $form->field($model,'price_1')->textInput([])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*二级广告价格:</td>
            <td>
                <?= $form->field($model,'price_2')->textInput([])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 115px;">*三级广告价格:</td>
            <td>
                <?= $form->field($model,'price_3')->textInput([])->label(false);?>
            </td>
        </tr>
        <tr style="text-align: center;">
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
    $("#advertpricesearch-advert_id").change(function() {
        var advertid = $("#advertpricesearch-advert_id option:checked").val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['ajaxplace'])?>',
            type : 'POST',
            ContentType: "application/json; charset=utf-8",
            dataType : 'json',
            data : {'advertid':advertid},
            success:function(phpdata){
//                var aa = JSON.stringify(phpdata);
                $('#advertpricesearch-type').empty();
                $('#advertpricesearch-time').empty();
                phpdata.type == 1?a="视频" : a="图片";
                $('#advertpricesearch-type').append('<option value='+phpdata.type+'>'+a+'</option>');
                $.each(phpdata.time,function (i,item) {
                    $('#advertpricesearch-time').append('<option value='+i+'>'+item+'</option>');
                })
            },
            error:function(){
//                layer.msg('操作失败！');
            }
        })
    })
    $('.btn-primary').click(function(){
        var price_1 = $('#advertpricesearch-price_1').val();
        var price_2 = $('#advertpricesearch-price_2').val();
        var price_3 = $('#advertpricesearch-price_3').val();
        if(price_1==null||price_1==undefined||price_1=="" ||price_2==null||price_2==undefined||price_2==""||price_3==null||price_3==undefined||price_3==""){
            layer.msg('所有价格不能为空！');
            return false;
        }
        if(isNaN(price_1)||isNaN(price_2)||isNaN(price_3)){
            layer.msg('价格只能为数字！');
            return false;
        }
        var advertid = $("#advertpricesearch-advert_id option:checked").val();
        var times = $("#advertpricesearch-time option:checked").val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['ajaxprice'])?>',
            type : 'POST',
            ContentType: "application/json; charset=utf-8",
            dataType : 'json',
            data : {'advertid':advertid,'time':times},
            success:function(phpdata){
                if(phpdata==1){
                    $('#w0').submit();
                }else{
                    layer.msg('该广告位参数已设置价格！请勿重复设置');
                    return false;
                }
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
