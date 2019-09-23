<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kucha\ueditor\UEditor;
cms\assets\AppAsset::register($this);
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
<div class="system-startup-form aa" >
    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
    ]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model,'thumbnail')->widget('yidashi\uploader\SingleWidget'); ?>
    <?= $form->field($model,'content')->widget('kucha\ueditor\UEditor',[]) ?>
    <div class="form-group">
        <?= Html::Button('保存', ['class' => 'btn btn-primary submit']) ?>
    </div>
    <input type="hidden" name="type" value="1">
    <span id="sp"></span>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    .aa {margin-left: 20px;width: 95%}
</style>
<script type="text/javascript">
    $(function(){
        $(".submit").click(function(){
            var data=$('#w0').serialize();
            var content = $('textarea[name="SystemTrain[content]"]').val();
            if(!content){
                layer.msg('内容不能为空',{icon:2});
                return false;
            }
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['createimgtext'])?>',
                type : 'POST',
                dataType : 'json',
                data : data,
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                }
            });
        })
    })
</script>

