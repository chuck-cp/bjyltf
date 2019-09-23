<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

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
<div>
    <?php $form = ActiveForm::begin([
        'action' => ['stock-removal'],
        'method' => 'post',
    ]);?>
    <table class="table table-hover" >
        <input type="hidden" name="shopid" value="<?=Html::encode($shop_id)?>"/>
        <input type="hidden" name="types" value="upshebei"/>
        <? foreach($screennum as $key=>$value): ?>
        <tr>
            <td style="width: 95px;">*设备编号:</td>
            <td style="width: 40%;"><input value="<?=Html::encode($value['number'])?>" disabled/></td>
            <td style="width: 30%;"><?=Html::encode($value['remark'])?></td>
            <td style="width: 40px;" class="shanchu"></td>
        </tr>
        <? endforeach; ?>
    </table>
    <p colspan="4" align="center"><a class="detail xinzeng">新增设备编号</a></p>
    </br>
    <p colspan="4" align="center"><?= Html::Button('提交',['class'=>'btn btn-primary'])?></p>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->endBody() ?>
</body>
    </html >
<?php $this->endPage() ?>

<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.xinzeng').on('click',function(){
        var htmls = '<tr class="parents">'
        htmls += '<td style="width: 95px;">*设备编号:</td>';
        htmls += '<td style="width: 40%;"><input type="text" class="screen" name="Screen[number][]" value=""/></td>';
        htmls += '<td style="width: 30%;"></td>';
        htmls += '<td style="width: 40px;" class="shanchu" onclick="deltr(this)">删除</td>';
        htmls += '</tr>';
        $('.table-hover').append(htmls);
    })

    $('.btn-primary').click(function(){
        var $inputArr = $('[name="Screen[number][]"]');
        var screenid = [];
        $inputArr.each(function(){
            screenid.push($(this).val());
        });
        var nary=screenid.sort();
        for(var i=0;i<screenid.length;i++){
            if(nary[i]==null || nary[i]==undefined || nary[i]==""){
                layer.alert("设备编号不能为空！");
                return false;
            }
            if (nary[i]==nary[i+1]){
                layer.alert("设备编号相同，请勿重复填写！");
                return false;
            }
        }
        $.ajax({
            url:'<?=Url::to(['check-screenid'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'number':screenid},
            success:function (resdata) {
                if(resdata ==1){
                    $('#w0').submit();
                }else if(resdata.errorid ==2){
                    layer.alert('设备编号：'+resdata.number+'已发货，请勿重复提交！');
                }else if(resdata.errorid ==3){
                    layer.alert('设备编号：'+resdata.number+'不在设备库内，请确认！');
                }else if(resdata.errorid ==4){
                    layer.alert('设备编号：'+resdata.number+'还未出库，请确认！');
                }
                return false;
            },error:function (error) {
//                layer.msg('操作失败！');
            }
        });
    })

    function deltr(obj){
        $(obj).parent('.parents').remove();
    }

</script>