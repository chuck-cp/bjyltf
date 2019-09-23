<?php

use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
\cms\assets\AppAsset::register($this);
use yii\bootstrap\Html;
use cms\models\SystemAddress;
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
    .fm{width:250px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html><head>
    <?php $this->head() ?>
</head>

<?php $this->beginBody(); ?>
<?$office = \cms\models\SystemOffice::findOne(['id'=>$kuid]);?>
<div class="member-search">
    <div class="">
        <h3><label style="margin: 10px 0 0 2%;"><?=Html::encode($office->office_name)?></label></h3>
        <?php echo $this->render('layout/output',['kuid'=>$kuid]);?>
        <?php  $form = ActiveForm::begin([
//            'action' => ['/ledmanage/frame/lot'],
            'method' => 'post',
        ]); ?>
        <?= $form->field($model, 'office_id')->hiddenInput(['value'=>$kuid])->label(false); ?>
        <div class="form-group" style="margin-top: 10px;margin-left: 15px;">
            <?=$form->field($model,'receive_member_id')->textInput(['class'=>'form-control fm'])->label('领用人');?>
        </div>
        <hr>
        <h3 style="margin-left: 10px;">设备编号：</h3>
        <div id="number">
            <div class="add">
                <div class=" mtop" style="display: flex">
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                    </div>
                </div>
                <div class=" mtop" style="display: flex">
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mtop row t-middle">
            <div class="col-xs-10">
                <?= Html::a(' 继续添加 +','javascript:void(0);',['class' => 'btn btn-primary keep']);?>
            </div>
        </div>
        <div class="mtop row t-middle">
            <div class="col-xs-10">
                <?= Html::Button('出库', ['class' => 'btn btn-success sub']) ?>
                <?= Html::a('取消','javascript:void(0);',['class' => 'btn btn-primary','id' => 'cancel']);?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/jquery-ui.js"></script>
<script type="text/javascript" src="/static/js/jquery.notify.js"></script>

<script type="text/javascript">
    //自动加载内部人员名单
    $(function(){
//        member =$("#membersearch-name").val();
//        deviceid = [];
        var datas = <?=$nmember?>;
        $("#systemdeviceframe-receive_member_id").autocomplete({source: datas});//制定领取人
    });

    //点击继续加载
    $('.keep').bind('click',function () {
        var adhtml = '';
            adhtml += '<div class=" mtop" style="display: flex"><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div></div><div class=" mtop" style="display: flex"><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div><div class="col-md-3"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control mleft" name="SystemDevice[device_number][]" placeholder="设备编号"></div></div></div>';
        $(adhtml).appendTo("#number .add");
    })

    //检查所填写的订单有无重复
    $(".sub").click(function () {
        var kuid = $('input[name="SystemDeviceFrame[office_id]"]').val();//当前办事处
        var member =$("#systemdeviceframe-receive_member_id").val();//制定领取人
        if(member==''){
            layer.alert('请填写领取人员');
            return false;
        }
        var deviceid = [];
        //设备编号
        $("#number .col-md-3 .mleft").each(function () {
            var num = $(this).val();
            if(num != ''){
                deviceid.push(num);
            }
        })
        if(deviceid.length < 1){
            layer.msg('请输入设备编号！');
            return false;
        }
        if(deviceid.length !== $.unique(deviceid).length){
            layer.msg('请勿输入重复设备编号！');
            return false;
        }
        var numids = $.unique(deviceid);
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['check-info'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'deviceid':numids,'kuid':kuid},
            success:function (resdata) {
                if(resdata ==true){
                    $.ajax({
                        url:'<?=\yii\helpers\Url::to(['/ledmanage/frame/lot'])?>',
                        type : 'POST',
                        dataType : 'json',
                        data : {'deviceid':numids,'member':member},
                        success:function (result) {
                            if(result == true){
                                layer.open({
                                    type: 1,
                                    skin: 'layui-layer-demo', //样式类名
                                    closeBtn: 1, //不显示关闭按钮
                                    anim: 2,
                                    shadeClose: true, //开启遮罩关闭
                                    content: '<div style="width: 200px;height: 200px;">此次共计出库 <font  style="color: red;size=10;">'+numids.length+'<\/font> 台设备！'
                                });
                                setTimeout(function(){
                                    window.parent.location.reload();
                                },2000);
                            }else{
                                layer.msg('出库失败！');
                            }
                        },
                        error:function (error) {
                            layer.msg('出库失败1！');
                        }
                    });
                }else{
                    layer.open({
                        type: 1,
                        skin: 'layui-layer-demo', //样式类名
                        closeBtn: 1, //显示关闭按钮
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: '<div style="width: 300px;height: 300px;">未入库设备：<\/br>'+resdata.emptynum.join(',<\/br>')+'<\/br>已出库设备：<\/br>'+resdata.outputnum.join(',<\/br>')+'<\/div>'
                    });
                    return false;
                }
            },
            error:function (error) {
                layer.msg('检测失败！');
            }
        })
    })
    //取消操作返回列表
    $("#cancel").bind('click', function () {
        layer.confirm('您确定取消本次操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            window.parent.location = "<?=\yii\helpers\Url::to(['index'])?>";
        }, function(){
            layer.msg('您已取消');
        });
    })
    //设备编号位数确认
    $.notifySetup({sound: '/static/audio/notify.wav'});
    $(function (){
	    $("#number .add").delegate("input","focus blur",function(e){
            var type= e.type;
            if (e.type=="focusout") {
                var xuhao_1=$(this).parents(".mtop").index();
                var xuhao_2=$(this).parents(".col-md-3").index();
                bm_num=$(this).val().length;
                nerong=$(this).val();
                // console.log(bm_num+"----"+nerong)
                // if(bm_num!=14){
                //     if(bm_num!=0){
                //          $('<span style="display:none"></span>').notify({sticky: true});
                //         layer.alert('设备编号不是14位，请确认！');
                //         $('#number .add').find('.mtop').eq(xuhao_1).find('input').eq(xuhao_2).addClass("biankuan");
                //     }
                //     if(bm_num ==0){
                //         $('#number .add').find('.mtop').eq(xuhao_1).find('input').eq(xuhao_2).removeClass("biankuan")
                //     }
                //     return false
                // }
                $('#number .add').find('.mtop').eq(xuhao_1).find('input').eq(xuhao_2).removeClass("bainkuan");
            };
	    });
    })
</script>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{display: inline-block;}
    .detail:hover{cursor:pointer;}
    #w0{#display: flex;justify-content:center;align-items:center; width:100%;}
    table th,table td{text-align: center; }
	.biankuan{ border:1px #F00 solid}
</style>