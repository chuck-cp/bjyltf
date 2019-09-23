<?php

use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

cms\assets\AppAsset::register($this);

$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

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

    <input <?if(in_array('101',$area_id)):?>checked="checked"<?endif;?> type="checkbox" class="whole" name="User[area_auth][]" value="101">全国
    <?foreach ($ProvinceArr as $k=>$pv):?>
        <ul style="list-style: none;">
            <li>
                <input class="sy_second" type="checkbox"><?echo $pv?>&nbsp;&nbsp;&nbsp;&nbsp;<br />
                <div style="padding:10px 0 0 30px;">
                <?foreach (\cms\models\SystemAddress::getAreasByPid($k) as $kk=>$pvv):?>
                    <input type="checkbox" name="User[area_auth][]" <?if(in_array($kk,$area_id)):?>checked="checked"<?endif;?>  value="<?echo $kk?>"><?echo $pvv?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?endforeach;?>
                </div>
            </li>
        </ul>
    <?endforeach;?>
    <?= Html::Button('提交',['class'=>'btn btn-primary','id'=>$model->id] )?>

    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.btn-primary').click(function(){
            var user_id=$(this).attr('id');
            var datas=$('#w0').serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['auth-area'])?>&user_id='+user_id,
                type : 'POST',
                dataType : 'json',
                data : datas,
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                }
            });
        })
    })
    $('.whole').on('click',function(){
        if($(this).prop('checked')==true){
            $(this).siblings('ul').find('input').prop('checked',true);
        }else{
            $(this).siblings('ul').find('input').prop('checked',false);
            $('.whole').prop('checked',false)
        }
    })
    //二级选中
    $('.sy_second').on('click',function(){
        if($(this).prop('checked')==true){
            $(this).siblings('div').find('input').prop('checked',true);
        }else{
            $(this).siblings('div').find('input').prop('checked',false)
        }
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
