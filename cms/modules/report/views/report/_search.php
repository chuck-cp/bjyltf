<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\AdvertPosition;
use cms\models\SystemAddress;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
$this->registerJs("
         $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-md-3');
                selObj.nextAll().find('select').find('option:not(:first)').remove();
                var parent_id = $(this).val();
                if(!parent_id){
                    return false;
                }
                $.ajax({
                    url: '".\yii\helpers\Url::to(['/member/member/address'])."',
                    type: 'POST',
                    dataType: 'json',
                    data:{'parent_id':parent_id},
                    success:function (phpdata) {
                        $.each(phpdata,function (i,item) {
                            selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                        })
                    },error:function (phpdata) {
                        layer.msg('获取失败！');
                    }
                })
            })
");
?>
<style type="text/css">
    .fm{
        width: 180px;
    }
</style>
<div class="order-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?=$form->field($model,'order_code')->textInput(['class'=>'form-control fm'])->label('订单号&nbsp;&nbsp;&nbsp;&nbsp;')  ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model,'member_name')->textInput(['class'=>'form-control fm'])  ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model,'total_day')->textInput(['class'=>'form-control fm'])  ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model,'screen_number')->textInput(['class'=>'form-control fm'])  ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?=$form->field($model,'examine_status')->dropDownList(['4'=>'播放中','5'=>'已完成'],['class'=>'form-control fm','prompt'=>'全部'])  ?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model,'advert_id')->dropDownList(AdvertPosition::getAllAdvertPos(),['class'=>'form-control fm','prompt'=>'全部'])->label('广告位&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')  ?>
            </div>
            <div class="col-md-3" style="width:auto;">
                <label class="control-label" for="ordersearch-order_date_starts_at">投放日期</label>
                <input type="text" class="form-control fm datepicker" placeholder="请选择日期" name="order_date_starts_at" />
                <spen>--</spen>
                <input type="text" class="form-control fm datepicker" placeholder="请选择日期" name="order_date_starts_at_end" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php  echo $form->field($model, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省&nbsp;&nbsp;&nbsp;&nbsp;') ?>
            </div>
            <div class="col-md-3">
                <?php  echo $form->field($model, 'city')->dropDownList(SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') ?>
            </div>
            <div class="col-md-3">
                <?php  echo $form->field($model, 'area')->dropDownList(SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区&nbsp;&nbsp;&nbsp;&nbsp;') ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?=  html::submitButton('导出列表',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>