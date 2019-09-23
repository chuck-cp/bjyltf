<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\config\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?/*= $form->field($model, 'old_cipher')->passwordInput(['maxlength' => true]) */?>
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::Button('提交', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function(){
        /*$.cookie('the_cookie', '12321312321');*/
        $(".btn-success").click(function(){
            var data=$("#w0").serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['modify','id'=>Yii::$app->user->identity->getId()])?>',
                type : 'POST',
                dataType : 'json',
                data : data,
                success:function (data) {
                    if(data.error==1){

                      //  $.cookie("advanced-pms", "", {expires: -1});

                       // example $.cookie('advanced-pms', null);
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                           parent.location.href="<?php echo \yii\helpers\Url::to(['login']);?>";
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        })
    })
</script>