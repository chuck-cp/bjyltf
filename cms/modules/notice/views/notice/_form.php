<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\notice\models\SystemNotice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="system-notice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget'); ?>

    <?= $form->field($model,'content')->widget('kucha\ueditor\UEditor',[]) ?>

<!--    --><?//= $form->field($model, 'top')->textInput() ?>
    <? echo $form->field($model, 'top')->radioList(['1'=>'推送至首页','0'=>'不推送'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    </div>

    <input type="hidden" name="filename" value="notice">
    <span id="sp"></span>
    <?php ActiveForm::end(); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">

    $(function () {
//        $('input:radio[name="SystemNotice[top]"]').click(function () {
//            var ind = $(this).parent().index();
//            if(ind == 0){
//                layer.open({
//                    type: 2,
//                    title: '选择推送目标',
//                    shadeClose: false,
//                    closeBtn:0,
//                    shade: 0.8,
//                    area: ['43%', '29%'],
//                    content: '<?//=\yii\helpers\Url::to(['/notice/notice/banner-info'])?>//' //iframe的url
//                });
//            }
//            if(ind == 1){
//                $('#sp input').remove();
//            }
//        })
    })

</script>