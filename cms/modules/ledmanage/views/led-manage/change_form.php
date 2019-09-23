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
                <div class="col-md-4 center weight">设备硬件编号(包装盒编码)</div>
<!--                <div class="col-md-5 center weight">设备软件编号(开机二维码)</div>-->
            </div>
            <div class="row mtop">
                <div class="col-md-1">1</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-1">2</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">3</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control mleft'])->label(false); ?>
                </div>
                <div class="col-md-1">4</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">5</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-1">6</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">7</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-1">8</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">9</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
                </div>
                <div class="col-md-1">10</div>
                <div class="col-md-4">
                    <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control  mleft'])->label(false); ?>
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
                adhtml += '<div class="row"><div class="col-md-1">'+(serial+i)+'</div><div class="col-md-4"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"><div class="help-block"></div></div></div><div class="col-md-1">'+(serial+i+1)+'</div><div class="col-md-4"><div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control  mleft" name="SystemDevice[device_number][]" placeholder="设备编号"><div class="help-block"></div></div></div></div> ';
            }
            $(adhtml).appendTo("#number .add");
        })

        //失去焦点  1.验证所填设备号库中是否已存在 2.检测有无重复,若重复标红 3.最后一个触发继续添加事件
        $("#number").on('blur','.col-md-4 .mleft',function () {
            var __this = $(this);
            var _pthisval=$(this).val();
            var thisval = __this.val();
            var trr = [];
            $("#number .col-md-4 .mleft").each(function () {
                var eachthis = $(this);
                var __thisval = $(this).val();
                if(__thisval){
                    trr.push(__thisval);
                }
            })
            var a=0;
            for(i=0;i<trr.length;i++){
                if(trr[i]==_pthisval){
                    a++;
                }
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
            //设备编号
            $("#number .col-md-4 .mleft").each(function () {
                var objthis = $(this);
                var __this = $(this).val();
                if(__this){
                    arr.push(__this);
                    var url = "<?=\yii\helpers\Url::to(['check-change'])?>";
                    var data = new Object();
                    data.number = __this;
                    data.kuid = $('input[name="SystemDevice[office_id]"]').val();
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
            if(arr.length < 1){
                layer.msg('请输入设备编号！');
                return false;
            }
            if(arr.length !== $.uniqueSort(arr).length){
                layer.msg('请勿输入重复设备编号！');
                return false;
            }
            if(flag == 1){
                layer.msg('请确认您填写的设备编号库存状态！');
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


