<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
$this->registerJs("
     $('#six').change(function () {
            $('.upload').parents('.up:visible').hide().siblings('.up').show();
        })
");
?>
<div class="system-startup-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
    ]); ?>
    <input type="hidden" name="filename" value="back-stage">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">素材名称：</label>
        <div class="col-sm-3">
            <?= $form->field($model,'name')->textInput(['readonly'=>'readonly'])->label(false);?>
        </div>
    </div>

    <div class="form-group up">
        <label for="inputEmail3" class="col-sm-2 control-label">图片：</label>
        <div class="col-sm-3 upload">
            <?= $form->field($model,'thumbnail')->widget('yidashi\uploader\MultipleWidget')->label(false); ?>
        </div>
    </div>
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