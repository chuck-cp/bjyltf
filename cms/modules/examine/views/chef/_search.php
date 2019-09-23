<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MemberSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("select[key='area']").change(function () {
            var parent_id = $(this).val();
            var type = $(this).attr('data');
            var selObj = $(this).parents('.col-xs-2');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
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

<style type="text/css">
    .search tbody tr td{
        vertical-align: middle;
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
<div class="member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
        </div>
<!--        <div class="col-xs-2 form-group">-->
<!--            --><?//=$form->field($model,'number')->textInput(['class'=>'form-control fm'])->label('编号');?>
<!--        </div>-->
        <div class="col-xs-2 form-group">
            <?=$form->field($model,'mobile')->textInput(['class'=>'form-control fm'])->label('电话');?>
        </div>
        <div class="col-xs-2 form-group" >
            <?= $form->field($model, 'examine_status')->dropDownList(['0'=>'待审核','1'=>'审核通过','2'=>'审核不通过'],['prompt'=>'全部','class'=>'form-control fm'])->label('审核状态'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','data'=>'provience','key'=>'area','class'=>'form-control fm'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','data'=>'city','key'=>'area','class'=>'form-control fm'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','data'=>'area','key'=>'area','class'=>'form-control fm'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($model->area),['prompt'=>'全部','data'=>'town','key'=>'area','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'school') ?>

    <?php // echo $form->field($model, 'education') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'emergency_contact_name') ?>

    <?php // echo $form->field($model, 'emergency_contact_mobile') ?>

    <?php // echo $form->field($model, 'emergency_contact_relation') ?>

    <?php // echo $form->field($model, 'status') ?>
    <?php ActiveForm::end(); ?>

</div>

<style type="text/css">
    .fm{width: 105px;display: inline-block;}
    .control-label{margin-top: 8px;}
</style>