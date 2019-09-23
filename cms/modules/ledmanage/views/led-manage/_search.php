<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use cms\modules\ledmanage\models\SystemDevice;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\ledmanage\models\search\SystemDeviceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-device-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <table class="grid table table-striped table-bordered search">
        <tr>
            <td>
                <p>设备硬件编号</p>
                <?=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); ?>
            </td>
            <td>
                <p>设备软件编号</p>
                <?=$form->field($model,'software_id')->textInput(['class' => 'form-control'])->label(false); ?>
            </td>
            <td>
                <p>厂家名称</p>
                <?=$form->field($model,'manufactor')->dropDownList(SystemDevice::getNamesByIndex('manufactor','', true),['class' => 'form-control fm','prompt'=>'全部'])->label(false); ?>
               <!-- --><?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
            <td>
                <p>GPS</p>
                <?=$form->field($model,'gps')->dropDownList(['0'=>'无','1'=>'有'],['prompt' => '全部','class' => 'form-control fm'])->label(false); ?>
               <!-- --><?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
            <td>
                <p>是否出库</p>
                <?=$form->field($model,'is_output')->dropDownList(['0'=>'未出库','1'=>'已出库'],['prompt' => '全部','class' => 'form-control fm'])->label(false); ?>
                <?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
            <td>
                <p>办事处</p>
                <?=$form->field($model,'office')->dropDownList(\cms\models\SystemOffice::officeSearch(),['prompt' => '全部','key'=>'office','class' => 'form-control office'])->label(false); ?>
                <!-- --><?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
            <td>
                <p>入库仓库</p>
                <?=$form->field($model,'storehouses')->dropDownList(\cms\models\SystemOffice::storehouse($model->office),['prompt' => '全部','class' => 'form-control'])->label(false); ?>
               <!-- --><?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
            <td>
                <p>设备规格</p>
                <?=$form->field($model,'spec')->dropDownList(SystemDevice::getNamesByIndex('spec','', true),['prompt' => '全部','class' => 'form-control'])->label(false); ?>
                <!-- --><?/*=$form->field($model,'device_number')->textInput(['class' => 'form-control'])->label(false); */?>
            </td>
        </tr>
        <tr>
            <td>
                <p>入库时间</p>
                <?= $form->field($model, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); ?>
                <?= $form->field($model, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
            </td>
            <td>
                <p>出库日期</p>
                <?= $form->field($model, 'stock_out_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); ?>
                <?= $form->field($model, 'stock_out_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); ?>
            </td>
            <td>
                <p>入库人：</p>
                <?= $form->field($model, 'in_manager_name')->textInput(['placeholder'=>'入库人','class'=>'form-control'])->label(false); ?>
            </td>
            <td>
                <p>出库人：</p>
                <?= $form->field($model, 'out_manager_name')->textInput(['placeholder'=>'出库人','class'=>'form-control'])->label(false); ?>
            </td>
            <td>
                <p>领取人：</p>
                <?= $form->field($model, 'receive_member_id')->textInput(['placeholder'=>'领取人手机','class'=>'form-control'])->label(false); ?>
                <?= $form->field($model, 'receive_member_name')->textInput(['placeholder'=>'领取人姓名','class'=>'form-control'])->label(false); ?>
            </td>
            <!--<td>
                <p>收货时间</p>
                <?/*= $form->field($model, 'goods_receipt_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker'])->label(false); */?>
                <?/*= $form->field($model, 'goods_receipt_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker'])->label(false); */?>
            </td>-->
            <td colspan="5">
                <br/>
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
<!--                --><?//= Html::a('添加设备', ['create'], ['class' => 'btn btn-success']) ?>
<!--                <br/>-->
<!--                <br/>-->
<!--                --><?//=  html::a('批量出库','javascript:;',['class' => 'btn btn-primary batch']); ?>
                <?=  html::submitButton('导出',['class' => 'btn btn-primary export', 'name'=>'search', 'value'=>0]); ?>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid #dddddd; width: 50%; margin-bottom: 20px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 20px;">
                    在库设备数：<?php echo $isoutput[0]?>
                </p>
            </th>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 20px;">
                    出库设备数：<?php echo $isoutput[1]?>
                </p>
            </th>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.office').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('td');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var id = $(this).val();
            if(!id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/ledmanage/led-manage/storehouse'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'id':id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        console.log(item);
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })

        })
    })
</script>
