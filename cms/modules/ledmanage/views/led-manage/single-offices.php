<?php
\cms\assets\AppAsset::register($this);
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
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

<?php $this->beginBody(); ?>
<?php echo $this->render('layout/oneoutput',['kuid'=>$kuid,'deviceid'=>$deviceid]);?>
<div class="member-search">

    <?php $from=ActiveForm::begin([
//        'action' => ['out-put-office'],
        'method' => 'post',
    ])?>

    <div class="rowtable">
<!--        style="display: flex;justify-content:center; width:100%;margin-left:0;margin-right:0 ;margin-top: 20px;"-->
        <input type="hidden" name="deviceid" value="<?=Html::encode($deviceid)?>" />
        <input type="hidden" name="kuid" value="<?=Html::encode($kuid)?>" />
        <div class="row">
            <?foreach($offices as $kku=>$vku):?>
                <span><input type="radio" name="kunum" value="<?=Html::encode($vku['id'])?>"><?=Html::encode($vku['office_name'])?></span></br>
            <?endforeach;?>
        </div>
        <div class="mtop row t-middle">
            <div class="col-xs-10">
            <?= Html::Button('确定', ['class' => 'btn btn-primary button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<!--<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>-->
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //出库
    $('.button').click(function(){
        var kuid = $('input[name="kunum"]:checked').val();
        if(!kuid){
            layer.msg('请选择需要调转的办事处！',{icon:2});
            return false;
        }
        var deviceid = $('input[name="deviceid"]').val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['out-put-office'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'kuid':kuid,'deviceid':deviceid},
            success:function (resdata) {
                if(resdata ==true){
                    layer.closeAll();
                    layer.msg('出库成功！',{icon:1});
                    setTimeout(function(){
                        window.parent.location.reload();
                    },1000);
                }else{
                    layer.msg('操作失败，请刷新页面后重新出库！',{icon:2});
                }
            },
            error:function (error) {
                layer.msg('操作失败！');
            }
        });
    })
</script>
<style type="text/css">
    span{display: flex;justify-content:center;align-items:center; width:20%;margin: 20px 0 0 0px;}
</style>