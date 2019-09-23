<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
cms\assets\AppAsset::register($this);
$this->registerJsFile('/static/js/tcplayer/videojs-ie8.js');
$this->registerCssFile('/static/css/tcplayer/tcplayer.css');
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
    <input type="hidden" name="filename" value="back-stage" style="margin-top: 100px;">
        <div class="col-sm-3 upload" >
            <?= $form->field($model,'content')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
        </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::Button('提交', ['class' =>  'btn btn-primary submit']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    .aa {margin-left: 10px;width: 85%}
</style>
<script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>
<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
<script type="text/javascript">
    $(function(){
        $(".submit").click(function(){
            var data=$('#w0').serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['covermap'])?>',
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




        $('#uploadVideoNow').on('click', function () {
            $('#uploadVideoNow-file').click();
        });
    })
</script>

