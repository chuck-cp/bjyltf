<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\search\ShopHeadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-headquarters-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<!--    <input type="hidden" name="id" value="--><?//=Html::encode($model->id)?><!--">-->
<!--    --><?//=$form->field($model,'member_id')->hiddenInput(['value'=>$model->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($model, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($model,'member_name')->textInput(['placeholder'=>'姓名','class'=>'form-control fm'])->label('业务合作人');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'company_name')->textInput(['class'=>'form-control fm'])->label('公司名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'branch_shop_name')->textInput(['class'=>'form-control fm'])->label('分店名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'examine_status')->dropDownList(['0'=>'待审核','1'=>'审核通过','2'=>'审核未通过'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请状态');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('.col-xs-2');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
            //alert(parent_id);
            if(!parent_id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'parent_id':parent_id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
    })
</script>