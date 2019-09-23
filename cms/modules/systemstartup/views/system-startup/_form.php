<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
$this->registerJs("
//     $('#six').change(function () {
//            $('.upload').parents('.up:visible').hide().siblings('.up').show();
//        })
");
?>
<div class="system-startup-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
    ]); ?>
    <input type="hidden" name="filename" value="back-stage">
        <!--<div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">对应版本：</label>
            <div class="col-sm-3">
                <?/* if($model->isNewRecord):*/?>
                    <?/*= $form->field($model,'version')->textInput()->label(false);*/?>
                <?/* else:*/?>
                    <?/*= $form->field($model,'version')->textInput(['readonly'=>'readonly'])->label(false);*/?>
                <?/* endif;*/?>
            </div>
        </div>-->
<!--        <div class="form-group">-->
<!--            <label for="sec" class="col-sm-2 control-label">启动页类型：</label>-->
<!--            <div class="col-sm-3">-->
<!--                --><?//= $form->field($model, 'type')->dropDownList(['1'=>'活动启动页','2'=>'开屏广告'],['class'=>'form-control','id'=>'sec'])->label(false) ?>
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group">-->
<!--            <label for="th" class="col-sm-2 control-label">用户可见次数：</label>-->
<!--            <div class="col-sm-3">-->
<!--                --><?//= $form->field($model, 'visibility')->dropDownList(['1'=>'每次可见','2'=>'首次可见'],['class'=>'form-control','id'=>'th'])->label(false) ?>
<!--            </div>-->
<!--        </div>-->
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">生效时间：</label>
            <div class="col-sm-3">
                <?= $form->field($model, 'start_at')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss',
                        'todayHighlight' => true
                    ]
                ])->label(false); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">结束时间：</label>
            <div class="col-sm-3">
            <?= $form->field($model, 'end_at')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayHighlight' => true
                ]
            ])->label(false); ?>
            </div>
        </div>
        <div class="form-group">
<!--        <label for="six" class="col-sm-2 control-label">图片有无连接：</label>-->
<!--        <div class="col-sm-3">-->
<!--            --><?//= $form->field($model, 'haslink')->dropDownList(['0'=>'无','1'=>'有'],['class'=>'form-control','id'=>'six'])->label(false) ?>
<!--        </div>-->
    </div>
        <? if(!$model->isNewRecord):?>
            <? if(is_string($model->start_pic)):?>
            <? if(substr($model->start_pic,0,4) == 'http'):?>
                <div class="form-group up">
                    <label for="inputEmail3" class="col-sm-2 control-label">图片：</label>
                    <div class="col-sm-3 upload">
                        <?= $form->field($model,'single_pic')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
                        <?= $form->field($model,'link')->textInput()->label('链接')?>
                    </div>
                </div>
<!--                <div class="form-group up" style="display:none">-->
<!--                    <label for="inputEmail3" class="col-sm-2 control-label">图片：</label>-->
<!--                    <div class="col-sm-3 upload">-->
<!--                        --><?//= $form->field($model,'start_pic')->widget('yidashi\uploader\MultipleWidget')->label(false); ?>
<!--                    </div>-->
<!--                </div>-->
            <? else:?>
<!--                <div class="form-group up">-->
<!--                    <label for="inputEmail3" class="col-sm-2 control-label">图片：</label>-->
<!--                    <div class="col-sm-3 upload">-->
<!--                        --><?//= $form->field($model,'start_pic')->widget('yidashi\uploader\MultipleWidget')->label(false); ?>
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group up" style="">
                    <label for="inputEmail3" class="col-sm-2 control-label">图片：</label>
                    <div class="col-sm-3 upload">
                        <?= $form->field($model,'single_pic')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
                        <?= $form->field($model,'link')->textInput()->label('链接')?>
                    </div>
                </div>
            <? endif;?>
            <? endif;?>
        <? else:?>
<!--            <div class="form-group up">-->
<!--                <label for="first" class="col-sm-2 control-label">图片：</label>-->
<!--                <div class="col-sm-3 upload">-->
<!--                    --><?//= $form->field($model,'start_pic')->widget('yidashi\uploader\MultipleWidget')->label(false); ?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group up" style="display:" >
                <label for="second" class="col-sm-2 control-label">图片：</label>
                <div class="col-sm-3 upload">
                    <?= $form->field($model,'single_pic')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
                    <?= $form->field($model,'link')->textInput()->label('链接')?>
                </div>
            </div>
        <? endif;?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton($model->isNewRecord ? '新建' : '保存', ['class' =>  'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">
    .row div{line-height: 30px;}
    .files .done{width: auto;}
    .remove{background: #ff0000;}
</style>