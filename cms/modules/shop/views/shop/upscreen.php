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
<input type="hidden" name="shop_id" value="<?=Html::encode($shop_id)?>">
<input type="hidden" name="maintain_type" value="<?=Html::encode($type)?>">
<table class="table table-striped table-bordered" style="width: 98%;margin: 20px 10px;">
    <tbody>
        <tr>
            <?if($type ==2):?>
                <td style="width: 120px;text-align: center;vertical-align:middle;"><label>更换屏幕数量:</label></td>
            <?elseif ($type ==3):?>
                <td style="width: 120px;text-align: center;vertical-align:middle;"><label>拆除屏幕数量:</label></td>
            <?elseif ($type ==4):?>
                <td style="width: 120px;text-align: center;vertical-align:middle;"><label>新增屏幕数量:</label></td>
            <?endif;?>
            <td><input type="text" class="form-control" name="replace_screen_number" oninput="value=value.replace(/[^\d]/g,'')" placeholder="屏幕数量" maxlength="2"></td>
        </tr>
        <tr>
            <td style="text-align: center;vertical-align:middle;"><label>备注:</label></td>
            <td><textarea class="form-control" name="description" value="" placeholder="备注" maxlength="120" rows="5"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><button type="button" class="btn btn-primary">确认</button></td>
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
    $(".btn-primary").click(function(){
        var num = $('input[name="replace_screen_number"]').val();
        if(!num){
            layer.msg('请输入需要维护的屏幕数量！');
            return false;
        }else{
            $('#w0').submit();
        }
    });
</script>