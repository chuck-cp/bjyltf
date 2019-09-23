<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\ledmanage\models\SystemDeviceFrame;
use dosamigos\datepicker\DatePicker;
use kartik\datetime\DateTimePicker;
use cms\modules\ledmanage\models\SystemDevice;
/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDevice */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .fm{width:200px;}
    .frame span {width: 250px;margin: 0 0 0 40px;}
</style>
<?$office = \cms\models\SystemOffice::findOne(['id'=>$kuid]);?>
<div class="system-device-frame-form">
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
            <?= $form->field($model, 'manufactor')->dropDownList(SystemDeviceFrame::getNamesByIndex('manufactor','', true),['class'=>'form-control','prompt'=>'全部'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">NFC：</label>
            <?= $form->field($model, 'nfc')->radioList(['1'=>'支持','2'=>'不支持'],['class' => 'form-control fm','value'=>'1'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">规格：</label>
            <?= $form->field($model, 'device_size')->dropDownList(SystemDeviceFrame::getNamesByIndex('spec','', true),['class' => 'form-control fm','prompt'=>'请选择规格'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">材质：</label>
            <?= $form->field($model, 'device_material')->dropDownList(SystemDeviceFrame::getNamesByIndex('material','', true),['class' => 'form-control fm','prompt'=>'请选择材质'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label for="receiving_at">入库日期：</label>
            <?= $form->field($model, 'receiving_at')->textInput(['placeholder'=>'入库日期','class'=>'form-control datepicker'])->label(false); ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">批次：</label>
            <?= $form->field($model, 'batch')->textInput(['placeholder'=>'批次','class'=>'form-control fm'])->label(false); ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">仓库：</label>
            <?= $form->field($model, 'storehouse')->dropDownList(SystemDeviceFrame::getNamesByIndex('storehouse','', true,$kuid),['class' => 'form-control fm','prompt'=>'请选择仓库'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <label for="exampleInputEmail2">品质：</label>
            <?= $form->field($model, 'device_level')->dropDownList(SystemDeviceFrame::getNamesByIndex('level','', true),['class' => 'form-control fm','prompt'=>'请选择品质'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="exampleInputEmail2">备注：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <?= $form->field($model, 'remark')->textarea(['placeholder'=>'备注信息','class'=>'form-control ','rows' => 4,'cols' => 180])->label(false); ?>
        </div>
    </div>
    <hr>
    <h4>设备编号：</h4>
    <div id="number">
        <div class="add">
            <div class="row mtop">
                <div class="col-md-12 frame">
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
                    <span><?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'画框编号','class'=>'form-control mleft'])->label(false); ?></span>
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
        //继续添加
        $('.keep').bind('click',function () {
            var adhtml = '';
                adhtml += '<span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span><span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span><span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span><span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span><span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span><span><div class="form-group field-systemdeviceframe-device_number"><input type="text" id="systemdeviceframe-device_number" class="form-control mleft" name="SystemDeviceFrame[device_number][]" placeholder="画框编号"><div class="help-block"></div></div></span>';
            $(adhtml).appendTo(".frame");
        });

        //失去焦点  1.验证所填设备号库中是否已存在 2.检测有无重复,若重复标红 3.最后一个触发继续添加事件
        $("#number").on('blur','.frame .mleft',function () {
            var __this = $(this);
            var _pthisval=$(this).val();
            var thisval = __this.val();
            var trr = [];
            var lensb = 0;
            $("#number .frame .mleft").each(function () {
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

        //检查所填写的编号有无重复
        $(".sub").click(function () {
            var arr = [];
            var flag = 0;
            // var rj = 0;
            var lensb = 0;
            //画框编号
            $("#number .frame .mleft").each(function () {
                var objthis = $(this);
                var __this = $(this).val();
                if(__this.length>0){
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
                }
            })
            if(flag == 1){
                layer.msg('您填写的画框编号可能已存在！');
                return false;
            }
            if(arr.length !== $.uniqueSort(arr).length){
                layer.msg('请勿输入重复画框编号！');
                return false;
            }
            if(arr.length < 1){
                layer.msg('请输入画框编号！');
                return false;
            }
        });
        //取消操作返回列表
        $("#cancel").bind('click', function () {
            layer.confirm('您确定取消本次操作？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                window.location = "<?=\yii\helpers\Url::to(['offices'])?>";
            }, function(){
                layer.msg('您已取消');
            });
        });

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


