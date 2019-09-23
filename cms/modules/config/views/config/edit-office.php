<?php
use yii\helpers\Html;
cms\assets\AppAsset::register($this);
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
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
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 yw">
            办事处名称：
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'office_name')->textInput(['class'=>'form-control'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 yw">
            仓库名称：
        </div>
    </div>
    <div class="row">
        <?if($model->storehouse):?>
            <?foreach (explode(',', $model->storehouse) as $v):?>
                <div class="col-md-3">
                    <?= $form->field($model, 'storehouse[]')->textInput(['class'=>'form-control', 'value'=>$v])->label(false) ?>
                </div>
            <?endforeach;?>
        <?else:?>
            <div class="col-md-3">
                <?= $form->field($model, 'storehouse[]')->textInput(['class'=>'form-control'])->label(false) ?>
            </div>
        <?endif;?>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::button('继续添加',['class' => 'btn btn-success keep']);?>
        </div>
    </div>
    <hr/>
    <hr>
    <?= Html::Button('保存', ['class' => 'btn btn-primary edit', 'name' => 'contact-button' ,'id'=>$model->id]) ?>
</div>

<?php \yii\widgets\ActiveForm::end(); ?>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<script type="text/javascript">
    $('.keep').click(function(){
        var obj = $(this).parents('.row');
        var html = '';
        html = '<div class="col-md-3"><div class="form-group field-systemconfig-manufactory required"><input type="text" id="systemconfig-manufactory" class="form-control" name="SystemOffice[storehouse][]" value=""><div class="help-block"></div></div></div>';
        obj.prev().append(html);
    })
    $('.edit').click(function(){
        var data=$('#w0').serialize();
        var id=$(this).attr('id');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['edit-office'])?>&id='+id,
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

</script>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
    }
</style>

