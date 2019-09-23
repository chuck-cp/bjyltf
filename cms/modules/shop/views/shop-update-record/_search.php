<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\search\ShopUpdateRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-update-record-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'shop_id')->textInput(['placeholder'=>'商家编号','class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'shop_name')->textInput(['placeholder'=>'原商家名称','class'=>'form-control fm'])->label('原商家名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'apply_name')->textInput(['placeholder'=>'原法人姓名','class'=>'form-control fm'])->label('原法人姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'apply_mobile')->textInput(['placeholder'=>'原法人电话','class'=>'form-control fm'])->label('原法人电话');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'company_name')->textInput(['placeholder'=>'原公司名称','class'=>'form-control fm'])->label('原公司名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'update_shop_name')->textInput(['placeholder'=>'更新后商家名称','class'=>'form-control fm'])->label('更新后商家名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'update_apply_name')->textInput(['placeholder'=>'更新后法人姓名','class'=>'form-control fm'])->label('更新后法人姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'update_apply_mobile')->textInput(['placeholder'=>'更新后法人电话','class'=>'form-control fm'])->label('更新后法人电话');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'update_company_name')->textInput(['placeholder'=>'更新后公司名称','class'=>'form-control fm'])->label('更新后公司名称');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'examine_status')->dropDownList(['0'=>'待审核','1'=>'审核通过','2'=>'审核驳回'],['class'=>'form-control fm','prompt'=>'全部'])->label('审核状态');?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
            <?=Html::a('发起变更',['initiate-change'],['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
