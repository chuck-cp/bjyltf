<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\ToolsClass;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemVersion */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
    $(function(){
        //更换图片路径
        $('.done img').attr('src','/static/img/file.png');
        var submitBtn = document.getElementsByClassName(\"btn-success\");
        var action = '$app_type';
        if(action == 'version'){
            submitBtn.onclick = function(event){
            var apk = $('.done input').val();
            if(!apk){
                    layer.msg('请上传安卓apk！');
                    var event = event || window.event;
                    event.preventDefault(); // 兼容标准浏览器
                    window.event.returnValue = false; // 兼容IE6~8
                }else{s
                    return true;
                }
            }
        }       
    })
");
?>

<div class="system-version-form">
    <?php $form = ActiveForm::begin(); ?>
    <?=$form->field($model,'app_type')->textInput(['value'=>$app_type,'type'=>'hidden'])->label(false)?><!--判断ios/anzhuo/pid-->
    <?= $form->field($model,'version_type')->dropDownList(['2'=>'主版本'/*,'1'=>'次版本','0'=>'修订版本'*/],['class'=>'form-control sel'])?>
    <?php if(isset($model->version)):?>
        <?=$form->field($model,'version')->textInput(['id'=>'sion'])?>
    <?php else:?>
        <?=$form->field($model,'version')->textInput(['id'=>'sion','value'=>"1.0.0"])?>
    <?php endif;?>
    <span style="color:#5e87b0">
    版本号规则： 版本号为三位<br />
    例：V1.0.0
    第一位为大版本号，第二位为小版本号，第三位为更新版本号
    </span>
    <p></p>
    <?= $form->field($model, 'desc')->textarea(['maxlength' => true,'rows'=>5])->label('更新说明') ?>

    <?php if($app_type == 1):?>
        <?= $form->field($model,'url')->widget('yidashi\uploader\SingleWidget')->label('上传APK'); ?>
        <input type="hidden" name="filename" value="system/version">
    <?php elseif($app_type == 2):?>
        <?=$form->field($model,'url')->textInput(['value'=>'https://itunes.apple.com/cn/app/%E7%8E%89%E9%BE%99%E4%BC%A0%E5%AA%92/id1335870775','readonly'=>'readonly'])?>
    <?php elseif($app_type == 3):?>
        <?= $form->field($model,'url')->widget('yidashi\uploader\SingleWidget')->label('上传APK'); ?>
    <?php endif;?>
    <?= $form->field($model, 'upgrade_type')->radioList(['1'=>'强制','2'=>'不强制'],['class'=>'label-group'])->label('是否强制升级'); ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

