<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\modules\ledmanage\models\SystemDevice;
\cms\assets\AppAsset::register($this);
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
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-inline',
        ],
    ]); ?>
    <?$offices=SystemDevice::SystemOfficeOne($model->office_id);?>
    <div class="row">
        <div class="form-group wid fl mleft fir" >
            <label for="exampleInputName2">厂家名称：</label>
            <?= $form->field($model, 'manufactor')->dropDownList(SystemDevice::getNamesByIndex('manufactor','', true),['class'=>'form-control','prompt'=>'全部'])->label(false) ?>
        </div>
        <div class="form-group wid fl mleft fir" >
            <label for="exampleInputName2">批次：</label>
            <?= $form->field($model,'batch')->textInput(['class'=>'form-control'])->label(false)?>
        </div>
    </div>
    <div class="row">
        <div class="form-group wid fl mleft fir" >
            <label for="exampleInputName2">GPS：</label>
            <?= $form->field($model,'gps')->radioList(['1'=>'有','0'=>'无'])->label(false)?>
        </div>
        <!--<div class="form-group wid fl mleft fir" >
            <label for="exampleInputName2">是否出库：</label>
            <?/*= $form->field($model,'is_output')->radioList(['1'=>'已出库','0'=>'未出库'])->label(false)*/?>
        </div>-->
        <div class="form-group wid fl mleft fir" >
            <label for="exampleInputName2">规格：</label>
            <?= $form->field($model, 'spec')->dropDownList(SystemDevice::getNamesByIndex('spec','', true),['class'=>'form-control'])->label(false) ?>
            <?/*= $form->field($model,'is_output')->radioList(['1'=>'已出库','0'=>'未出库'])->label(false)*/?>
        </div>
    </div>
    <div class="row">
        <div class="form-group wid fl mleft fir">
            <label for="exampleInputEmail2">设备编号：</label>
            <?= $form->field($model,'device_number')->textInput(['class'=>'form-control'])->label(false)?>
        </div>
        <div class="form-group wid fl mleft fir">
            <label for="exampleInputEmail2">软件编号：</label>
            <?= $form->field($model,'software_id')->textInput(['class'=>'form-control'])->label(false)?>
        </div>
    </div>
    <div class="row">
        <div class="form-group wid fl mleft fir">
            <label for="exampleInputEmail2">办事处：</label><br />
            <?echo $offices['office_name']?>
        </div>
        <div class="form-group wid fl mleft fir">
            <label for="exampleInputEmail2">仓库：</label>
            <?= $form->field($model, 'storehouse')->dropDownList(explode(',',$offices['storehouse']),['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group wid fir" >
            <label for="exampleInputName2">备注：</label>
            <?= $form->field($model,'remark')->textarea(['class'=>'form-control'])->label(false)?>
        </div>
    </div>
    <div class="row t-middle">
        <?= Html::submitButton('确定', ['class' => 'btn btn-success']) ?>
        <?= Html::a('取消','javascript:void(0)',['class' => 'btn btn-primary','id' => 'canncel']);?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php $this->endBody() ?>
    </body>
    </html >
<?php $this->endPage() ?>
<style type="text/css">
    .wid{width: 210px;}
    .fl{float: left;}
    .fir{margin-left: 22px;}
</style>