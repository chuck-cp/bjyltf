<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\config\models\search\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '付款配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
//    'action' => [''],
    'method' => 'post',
]); ?>
<div class="system-config-index">
    <div class="row">
        <div class="col-md-2 ">
            底部菜单配置
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-3">
            <? echo $form->field($model, 'content')->radioList(['1'=>'开启','0'=>'不开启'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= Html::Button('保存', ['class' => 'btn btn-primary submit', 'name' => 'contact-button']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<style type="text/css">
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
        width: 80px;
    }
</style>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.submit').click(function(){
            var data=$('#w0').serialize();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['bottom-menu-advert'])?>',
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
            })
        })
    })
</script>