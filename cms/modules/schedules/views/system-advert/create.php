<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */
/* @var $form yii\widgets\ActiveForm */
$this->title = '新增广告';
?>

<div class="system-advert-form">

    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-hover" >
        <tr>
            <td style="width: 95px;">*广告位:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_position_key')->dropDownList(['B'=>'等待日广告'],['class'=>'form-control fm'])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*广告名称:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_name')->textInput([])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*店铺名称:</td>
            <td colspan="3">
                <?= $form->field($model,'shop_name')->textInput([])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*链接地址:</td>
            <td colspan="3">
                <?= $form->field($model,'link_url')->textInput([])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*过期时间:</td>
            <td colspan="3">
                <?= $form->field($model,'over_at')->textInput(['class'=>'form-control datepicker fm collection-width','value'=>$model->over_at?date('Y-m-d',$model->over_at):''])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放时间:</td>
            <td colspan="3">
                <?=$form->field($model,'start_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                <?=$form->field($model,'end_at')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
            </td>
        </tr>
        <!--<tr>
            <td style="width: 95px;">*投放地区:</td>
            <td colspan="3">
                <?/*= $form->field($model,'area')->dropDownList([''=>'全国'],['class'=>'form-control','prompt'=>'请选择'])->label(false)*/?>
            </td>
        </tr>-->
        <tr>
            <td style="width: 95px;">*投放时长:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_time')->dropDownList(['3分钟'=>'3分钟','5分钟'=>'5分钟'],['class'=>'form-control fm','prompt'=>'请选择'])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放频次:</td>
            <td colspan="3">
                <?= $form->field($model,'throw_rate')->dropDownList(['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9'],['class'=>'form-control fm','prompt'=>'请选择'])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*上传素材:</td>
            <td colspan="3">
                <?= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
            </td>
        </tr>
    </table>
    <div class="form-group">
        <?= Html::Button('提交',['class'=>'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.btn-primary').click(function(){
        var data=$('#w0').serialize();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['create'])?>',
            type : 'POST',
            dataType : 'json',
            data : data,
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        self.location=document.referrer;
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
</script>