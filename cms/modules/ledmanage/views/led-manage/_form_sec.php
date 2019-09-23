<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use kartik\datetime\DateTimePicker;
use cms\modules\ledmanage\models\SystemDevice;
/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDevice */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    #number .field-systemdevice-device_number{ display: block}
    #number .field-systemdevice-software_id{display: block}
    #number .col-md-1{text-align: right}
    .fm{
        width:164px;
    }
    .field-systemdevice-remark{
        margin-top: 20px;
        width: 59%;
    }
    .col-md-1{
        font-weight: 700;
    }
</style>
<?$office = \cms\models\SystemOffice::findOne(['id'=>$kuid]);?>
<div class="system-device-form">
    <h3><label style="margin: 0px 0px 5px 0px;"><?=Html::encode($office->office_name)?></label></h3>
    <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-inline',
            ],
    ]); ?>
    <h4>基础信息：</h4>
    <?= $form->field($model, 'office_id')->hiddenInput(['value'=>$kuid])->label(false); ?>
    <div class="row">
        <div class="col-md-3">
            <label for="exampleInputName2">厂家名称：</label>
            <?= $form->field($model, 'manufactor')->dropDownList(SystemDevice::getNamesByIndex('manufactor','', true),['class'=>'form-control','prompt'=>'全部'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">GPS：</label>
            <?= $form->field($model, 'gps')->radioList(['1'=>'有','0'=>'无'],['class' => 'form-control fm','value'=>'1'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">规格：</label>
            <?= $form->field($model, 'spec')->dropDownList(SystemDevice::getNamesByIndex('spec','', true),['class' => 'form-control fm','prompt'=>'请选择规格'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label for="receiving_at">入库日期：</label>
            <?= $form->field($model, 'receiving_at')->textInput(['placeholder'=>'入库日期','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">批次：</label>
            <?= $form->field($model, 'batch')->textInput(['placeholder'=>'批次','class'=>'form-control fm'])->label(false); ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">仓库：</label>
            <?= $form->field($model, 'storehouse')->dropDownList(SystemDevice::getNamesByIndex('storehouse','', true,$kuid),['class' => 'form-control fm','prompt'=>'请选择仓库'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="exampleInputEmail2">备注：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <?= $form->field($model, 'remark')->textarea(['placeholder'=>'备注信息','class'=>'form-control ','rows' => 3,'cols' => 5])->label(false); ?>
        </div>
    </div>
    <hr>
    <h4>设备编号：</h4>
    <div id="number">
        <div class="add">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-4 center weight">设备硬件编号(包装盒编码)</div>
                <div class="col-md-5 center weight">设备软件编号(开机二维码)</div>
            </div>
            <div class="row mtop">
                <div class="col-md-1">1</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'software_id[]')->textInput(['placeholder'=>'软件编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">2</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'software_id[]')->textInput(['placeholder'=>'软件编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">3</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'software_id[]')->textInput(['placeholder'=>'软件编号','class'=>'form-control mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">4</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'software_id[]')->textInput(['placeholder'=>'软件编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">5</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'software_id[]')->textInput(['placeholder'=>'软件编号','class'=>'form-control  mleft'])->label(false); ?>
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
           <?= Html::submitButton('确定', ['class' => 'btn btn-success sub']) ?>
           <?= Html::a('取消','javascript:void(0);',['class' => 'btn btn-primary','id' => 'cancel']);?>
       </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //$('.field-systemdevice-device_number,.field-systemdevice-software_id').removeClass('form-group');
        //继续添加
        $('.keep').bind('click',function () {
            //find the last number
            var serial = $("#number").find(".col-md-1:last").html();
            var adhtml = '';
            for(var i=0; i<5; i++){
                serial ++;
                adhtml += '<div class="row"><div class="col-md-1">'+serial+'</div><div class="col-md-4"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"><div class="help-block"></div></div></div><div class="col-md-5"><div class="form-group field-systemdevice-software_id"><input type="text" id="systemdevice-software_id" class="form-control  mleft" name="SystemDevice[software_id][]" placeholder="软件编号"><div class="help-block"></div></div></div></div> ';
            }
            $(adhtml).appendTo("#number .add");
        })

        //失去焦点  1.验证所填设备号库中是否已存在 2.检测有无重复,若重复标红 3.最后一个触发继续添加事件
        $("#number").on('blur','.col-md-4 .mleft',function () {
            var __this = $(this);
            var _pthisval=$(this).val();
            var thisval = __this.val();
            var trr = [];
            var lensb = 0;
            $("#number .col-md-4 .mleft").each(function () {
                var eachthis = $(this);
                var __thisval = $(this).val();
                if(__thisval){
                    // if(__thisval.length >0 ){//&& __thisval.length !=14
                    //     lensb = 1;
                    // }
                    trr.push(__thisval);
                }
            })
            var a=0;
            for(i=0;i<trr.length;i++){
                if(trr[i]==_pthisval){
                    a++;
                }
            }
            // if(lensb == 1){
            //     layer.msg('您填写的设备编号必须为14位数字！');
            //     __this.css({'border':'1px solid red'});
            //     return false;
            // }
            if(a>1){
                __this.css({'border':'1px solid red'});
                return false;
            }else{
                __this.css({'border':'1px solid green'});
            }
        })
        $("#number").on('blur','.col-md-5 .mleft',function () {
            var __this = $(this);
            var _pthisval=$(this).val();
            var index = __this.parents('.col-md-5').siblings('.col-md-1').html();
            var lengrj = 0;
            /*
            if((index) % 5 == 0){
                $('.keep').trigger('click');
                //新添加的第一个input框被选中
                $(this).parents('.row').next().find('.col-md-4 input').focus();
            }
           */
            var thisval = __this.val();
            var trr = [];
            $("#number .col-md-5 .mleft").each(function () {
                var eachthis = $(this);
                var __thisval = $(this).val();
                if(__thisval){
                    // if(__thisval.length>0 ){//&& __thisval.length !=20 && __thisval.length !=19
                    //     lengrj = 1;
                    // }
                    trr.push(__thisval);
                }
            })
            var a=0;
            for(i=0;i<trr.length;i++){
                if(trr[i]==_pthisval){
                    a++;
                }
            }
            if(lengrj == 1){
                layer.msg('您填写的软件编号长度有误！');
                __this.css({'border':'1px solid red'});
                return false;
            }
            if(a>1){
                __this.css({'border':'1px solid red'});
                return false;
            }else{
                __this.css({'border':'1px solid green'});
            }
        })

        //检查所填写的订单有无重复
        $(".sub").click(function () {
            var arr = [];
            var flag = 0;
            var rj = 0;
            var lensb = 0;
            //设备编号
            $("#number .col-md-4 .mleft").each(function () {
                var objthis = $(this);
                var __this = $(this).val();
                if(__this.length>0 ){//&& __this.length!=14
                    lensb = 1;
                }
                if(__this){
                    arr.push(__this);
                    var url = "<?=\yii\helpers\Url::to(['check-unique'])?>";
                    var data = new Object();
                    data.number = __this;
                    var parameters = new Object();
                    parameters._data = data;
                    parameters._url = url;
                    parameters._success = false;
                    parameters._error = false;
                    if(sendAj(parameters)){
                        objthis.css({'border':'1px solid red'});
                        flag = 1;
                        return false;
                    }
                    //判断有无软件编号
                    var soft= objthis.parents('.col-md-4').siblings('.col-md-5').find('.mleft').val();
                    if(!soft){
                        rj = 1;
                        return false;
                    }
                }
            })
            if(flag == 1){
                layer.msg('您填写的设备编号可能已存在！');
                return false;
            }
            // if(lensb ==1){
            //     layer.msg('您填写的设备编号必须为14位数字！');
            //     return false;
            // }
            if(arr.length !== $.uniqueSort(arr).length){
                layer.msg('请勿输入重复设备编号！');
                return false;
            }
            if(rj == 1){
                layer.msg('设备编号和软件编号必须同时填写！');
                return false;
            }

            if(arr.length < 1){
                layer.msg('请输入设备编号！');
                return false;
            }
            var srr = [];
            var slag = 0;
            var sb = 0;
            var lenrj = 0;
            //软件编号
            $("#number .col-md-5 .mleft").each(function () {
                var objthis = $(this);
                var __this = $(this).val();
                // if(__this.length>0 ){//&& __this.length!=20  && __this.length!=19
                //     lenrj = 1;
                // }
                if(__this){
                    srr.push(__this);
                    var url = "<?=\yii\helpers\Url::to(['check-soft'])?>";
                    var data = new Object();
                    data.number = __this;
                    var parameters = new Object();
                    parameters._data = data;
                    parameters._url = url;
                    parameters._success = false;
                    parameters._error = false;
                    if(sendAj(parameters)){
                        objthis.css({'border':'1px solid red'});
                        slag = 1;
                        return false;
                    }
                    //判断有无软件编号
                    var soft= objthis.parents('.col-md-5').siblings('.col-md-4').find('.mleft').val();
                    if(!soft){
                        sb = 1;
                        return false;
                    }
                }
            })
            if(slag == 1){
                layer.msg('您填写的软件编号可能已存在！');
                return false;
            }
            if(srr.length !== $.uniqueSort(srr).length){
                layer.msg('请勿输入重复软件编号！');
                return false;
            }
            if(sb == 1){
                layer.msg('设备编号和软件编号必须同时填写！');
                return false;
            }
            if(srr.length < 1){
                layer.msg('请输入软件编号！');
                return false;
            }
            if(lenrj ==1){
                layer.msg('您填写的软件编号长度有误！');
                return false;
            }
        })
        //取消操作返回列表
        $("#cancel").bind('click', function () {
            layer.confirm('您确定取消本次操作？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                window.location = "<?=\yii\helpers\Url::to(['offices'])?>";
            }, function(){
                layer.msg('您已取消');
            });
        })

        //执行ajax
        function sendAj(parameters) {
            var $data = parameters._data;
            var $url = parameters._url;
            var $success = parameters._success;
            var $error = parameters._error;
            var isTrue = 0;
            $.ajax({
                url: $url,
                type: 'POST',
                data: $data,
                async: false,
                success:function (phpdata) {
                    if(!phpdata){
                        isTrue = 1;
                    }
                    if($success){
                        return false;
                    }
                },error:function () {
                    return false;
                }
            })
            return isTrue;
        }
    })

</script>


