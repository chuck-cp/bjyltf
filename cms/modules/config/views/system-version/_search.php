<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$action = $this->context->action->id;
?>
<style type="text/css">
    .detail,.stop{cursor: pointer;}
</style>
<div class="system-version-search">

    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?= $form->field($model, 'version')->textInput(['class'=>'form-control'])->label('版本号') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($model, 'upgrade_type')->dropDownList(['1'=>'强制升级','2'=>'不强制'],['prompt'=>'全部','class'=>'form-control'])->label('是否强制升级') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($model, 'create_at')->dropDownList(['1'=>'正序排列','2'=>'倒序排列'],['prompt'=>'全部','class'=>'form-control'])->label('发布时间') ?>
        </div>
        <div class="col-xs-2 form-group" style="margin-top: 23px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //查看修改
        $('.detail').click(function () {
            var id = $(this).attr('id');
            var index = layer.open({
                type: 2,
                title: '',
                shadeClose: true,
                shade: 0.8,
                area: ['780px', '55%'],
                content: '<?=\yii\helpers\Url::to(['detail'])?>&id='+id //iframe的url
            });
        })
        //停用
        $('.stop').bind('click',function () {
            var __this = $(this);
            var id = $(this).attr('id');
            layer.confirm('您确定要停用此版本吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url:"<?=\yii\helpers\Url::to(['stop'])?>",
                    data:{'id':id},
                    type:'POST',
                    dataType:'json',
                    success:function (phpdata) {
                        if(phpdata){
                            __this.parent('td').prev().prev().html('停运');
                            __this.hide();
                            layer.msg('已停用');
                        }else{
                            layer.msg('停用失败');
                        }
                    },eror:function (phpdata) {
                        layer.msg('操作失败');
                    }
                });
            }, function(){
                layer.msg('您已取消');
            });
        })
    })
</script>