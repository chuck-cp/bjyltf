<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\config\models\SystemBank */

$this->title = '添加银行';
$this->params['breadcrumbs'][] = ['label' => '银行管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-bank-create">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <div class="system-bank-form">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model,'bank_logo')->widget('yidashi\uploader\SingleWidget'); ?>
        <div class="form-group">
            <?= Html::Button('保存', ['class' => 'btn btn-success']) ?>
        </div>
        <input type="hidden" name="filename" value="bank">
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.btn-success').click(function(){
            var bank_name = $('input[name="SystemBank[bank_name]"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['exist'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'bank_name':bank_name},
                success:function (data) {
                    if(data==1){
                        $('#w0').submit();
                    }else{
                        layer.msg('该银行已存在',{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                    return false;
                }
            });
        })
    })
</script>