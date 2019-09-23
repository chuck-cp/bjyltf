<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\LoginForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\member\models\search\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>
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
            <?=$form->field($model, 'admin_area')->dropDownList([],['prompt'=>'全部','class'=>'form-control fm admin'])->label('业务区域') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'count_price')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('收益总额') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'inside')->dropDownList(['0'=>'否','1'=>'是'],['prompt'=>'全部','class'=>'form-control fm'])->label('内部人员') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'electrician')->dropDownList(['2'=>'否','1'=>'是'],['prompt'=>'全部','class'=>'form-control fm'])->label('是否为电工') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'company_electrician')->dropDownList(['2'=>'否','1'=>'是'],['prompt'=>'全部','class'=>'form-control fm'])->label('是否为内部电工') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($model, 'invite_id')->dropDownList(['2'=>'否','1'=>'是'],['prompt'=>'全部','class'=>'form-control fm'])->label('是否为合作推广人') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
            <?if(LoginForm::checkPermission('/export/power/member_export')):?>
                <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
            <?endif;?>
            <?/*if(in_array(Yii::$app->user->identity->username,['ylcmbeijing','ylcmshanghai','ylcmgaungzhou','ylcmgaungzhou','ylcmtianjin','ylcmhangzhou','shaoshuwei','wangxiaojuan','wuyanfeng'])):*/?><!--
            <?/*else:*/?>
                <?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
            --><?/*endif;*/?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<style type="text/css">
    .fm{width: 105px;display: inline-block;}
</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //点击切换地区
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
        //初始化业务区域
        var areas = '<?=\cms\modules\member\models\Member::getSystemAdminArea()?>';
        //console.log(areas);
        if(areas){
            $.each(JSON.parse(areas),function (i,item) {
                $('.admin').append('<option value='+i+'>'+item+'</option>');
            })
        }
    })
</script>