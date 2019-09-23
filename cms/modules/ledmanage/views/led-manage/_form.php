<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\SystemDevice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-device-form">

    <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-inline',
            ],
    ]); ?>

<!--    --><?//= $form->field($model, 'device_number')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'manufactor')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'batch')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'gps')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'receiving_at')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'is_output')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'status')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'create_at')->textInput() ?>
    <h4>基础信息：</h4>
        <div class="form-group">
            <label for="exampleInputName2">厂家名称：</label>
            <?= $form->field($model, 'manufactor')->textInput(['maxlength' => true,'class'=>'col-xs-2 form-control fm'])->label(false) ?>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">GPS：</label>
            <?= $form->field($model, 'gps')->radioList(['1'=>'有','0'=>'无'],['class' => 'col-xs-2 fm','value'=>'0'])->label(false) ?>
        </div>
        <div class="form-group">
            <label for="receiving_at">收货日期：</label>
            <?= $form->field($model, 'receiving_at')->textInput(['placeholder'=>'收货日期','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">批次：</label>
            <?= $form->field($model, 'batch')->textInput(['placeholder'=>'批次','class'=>'form-control fm'])->label(false); ?>
        </div>
        <div class="">
                <label for="exampleInputEmail2">备注：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <?= $form->field($model, 'remark')->textarea(['placeholder'=>'备注信息','class'=>'form-control ','rows' => 3,'cols' => 5])->label(false); ?>

        </div>
    <hr>
    <h4>设备编号：</h4>
    <div id="number">
<!--        <label for="exampleInputEmail2">设备编号11：</label>-->
        <div class="form-group add">
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>
            <?= $form->field($model, 'device_number[]')->textInput(['placeholder'=>'设备编号','class'=>'form-control fm mleft'])->label(false); ?>


        </div>

    </div>
    <div class="mtop t-middle">
        <div class="clo-xs-2">
            <?= Html::a(' 继续添加 +','javascript:void(0);',['class' => 'btn btn-primary keep']);?>
        </div>
    </div>
    <div class="mtop t-middle">
       <div class="clo-xs-3">
           <?= Html::submitButton('确定', ['class' => 'btn btn-success sub']) ?>
           <?= Html::a('取消','javascript:void(0);',['class' => 'btn btn-primary','id' => 'cancel']);?>
       </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<style type="text/css">
    .fm{
        width:164px;
    }
    .field-systemdevice-remark{
        margin-top: 20px;
        width: 72.2%;
    }
</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //继续添加
        $('.keep').bind('click',function () {
            var adhtml = '';
            for(var i=0; i<48; i++){
                adhtml += '<div class="form-group field-systemdevice-device_number"><input type="text" id="systemdevice-device_number" class="form-control fm mleft" name="SystemDevice[device_number][]" placeholder="设备编号"><div class="help-block"></div></div> ';
            }
            $(adhtml).appendTo("#number .add");
        })
        //失去焦点  1.验证所填设备号库中是否已存在 2.检测有无重复,若重复标红 3.最后一个触发继续添加事件
        $("#number").on('blur','.mleft',function () {
            var __this = $(this);
            var _pthisval=$(this).val();
            var index = __this.parent('.field-systemdevice-device_number').index();
            if((index + 1) % 48 == 0){
                $('.keep').trigger('click');
            }
            var thisval = __this.val();
            var trr = [];
            $("#number .mleft").each(function () {
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
               __this.css({'border':'1px solid red'})
           }
        })
        //检查所填写的订单有无重复
        $(".sub").click(function () {
            var arr = [];
            var flag = 0;
            $("#number .mleft").each(function () {
                var objthis = $(this);
                var __this = $(this).val();
                if(__this){
                    arr.push(__this);
                }
                if(__this){
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
                    }
                }
            })
            if(flag == 1){
                layer.msg('您填写的设备编号可能已存在！');
                return false;
            }
            if(arr.length !== $.uniqueSort(arr).length){
                layer.msg('请勿输入重复设备编号！');
                return false;
            }
            if(arr.length < 1){
                layer.msg('请输入设备编号！');
                return false;
            }
        })
        //取消操作返回列表
        $("#cancel").bind('click', function () {
            layer.confirm('您确定取消本次操作？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                window.location = "<?=\yii\helpers\Url::to(['index'])?>";
            }, function(){
                layer.msg('您已取消');
            });
        })
        //失去焦点验证所填设备号库中是否已存在
//        $("#number .mleft").blur(function () {
//            __val = $(this).val();
//            if(__val){
//                var url = "<?//=\yii\helpers\Url::to(['check-unique'])?>//";
//                var data = new Object();
//                    data.number = __val;
//                var parameters = new Object();
//                parameters._data = data;
//                parameters._url = url;
//                parameters._success = false;
//                parameters._error = '此设备已添加，请勿重复添加';
//                sendAj(parameters);
//
//            }
//        })
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


