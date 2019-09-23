<?php
use yii\bootstrap\Html;
use common\libs\ToolsClass;
use cms\modules\screen\models\Screen;
use yii\widgets\ActiveForm;
use cms\modules\examine\models\ShopScreenReplaceList;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
    .fm{width: 450px;display: inline-block;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?php $form = ActiveForm::begin([
    'action'=>['screen-replace'],
    'method' =>'post',
]); ?>
<input type="hidden" name="shopid" value="<?=Html::encode($shop_id)?>">
<table class="table table-striped table-bordered" style="width: 98%;margin: 20px 10px;">
    <thead>
        <tr>
            <!--<th><input type="checkbox" class="select-on-check-all" name="selection_all" value="0"></th>-->
            <th width="5%">选择</th>
            <th width="20%">屏幕硬件编号</th>
            <th width="10%">状态</th>
            <th width="15%">离线时间</th>
            <th>更换原因</th>
        </tr>
    </thead>
    <tbody >
    <?foreach($modelarray as $key=>$value):?>
        <tr class="restatus">
            <td style="text-align: center;">
<!--                --><?//if($value['status'] == 3):?>
<!--                    <input type="checkbox" class="select-on-check" name="number[]" value="--><?//=Html::encode($value['number'])?><!--" checked="checked" disabled="disabled">-->
<!--                --><?//else:?>
                    <input type="checkbox" class="select-on-check checkbad" name="number[]" value="<?=Html::encode($value['number'])?>">
<!--                --><?//endif;?>
            </td>
            <td><?=Html::encode($value['number'])?></td>
            <td><?=Html::encode(Screen::getScreenStatus($value['status']))?></td>
            <td>
                <?if($value['status'] == 0):?>
                    <?=Html::encode('---')?>
                <?elseif($value['status'] == 1):?>
                    <?=Html::encode('0000-00-00 00:00:00')?>
                <?else:?>
                    <?=Html::encode(ToolsClass::timediff(time(),strtotime($value['offline_time'])))?>
                <?endif;?>
            </td>
            <td><input type="text" class="form-control fm" name="replace_desc[]" value="" placeholder="更换原因" disabled></td>
        </tr>
    <?endforeach;?>
        <tr>
            <td colspan="5" style="text-align: center;"><button type="button" class="btn btn-primary">确认更换</button></td>
        </tr>
    </tbody>
</table>
<?php ActiveForm::end(); ?>
<?php $this->endBody() ?>
</body>
    </html >
<?php $this->endPage() ?>

<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $(".select-on-check").click(function (){
        if($(this).is(':checked')) {
            $(this).parents("tr").find(".form-control").removeAttr("disabled")
        }else{
            $(this).parents("tr").find(".form-control").attr("disabled","disabled")
        }
    })
    var zt=0;
    $(".btn-primary").click(function(){
        var gs=$(".restatus").find(".checkbad").length;
        for(i=0;i<gs;i++){
            var xz=$(".restatus").find(".checkbad").eq(i).is(':checked');
            if(xz){
                var zhi=$(".restatus").find(".checkbad").eq(i).parents("tr").find(".form-control").val();
                if(zhi.length>100){
                    zt=2;
                    break;
                }else if(zhi){
                    zt=1;
                }else{
                    zt=0;
                    break;
                }
            }
        }
        if(zt==1){
            $('#w0').submit();
        }else if(zt==2){
            layer.msg('换屏原因不得超过100个字符,请重新输入！');
            return false;
        }else{
            layer.msg('请选择需要更换的屏幕并填写更换原因！');
            return false;
        }
    });
</script>