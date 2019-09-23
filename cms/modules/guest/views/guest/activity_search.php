<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\search\ActivityDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('td');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
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
<div class="activity-detail-search">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>推荐人</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>推荐账号</p>
                    <?php  echo $form->field($searchModel, 'member_mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺联系人</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺联系方式</p>
                    <?php  echo $form->field($searchModel, 'apply_mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>业务合作人</p>
                    <?php  echo $form->field($searchModel, 'custom_member_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>业务合作人账号</p>
                    <?php  echo $form->field($searchModel, 'custom_member_mobile')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>

                <td>
                    <p>店铺名称</p>
                    <?php  echo $form->field($searchModel, 'shop_name')->textInput(['class'=>'form-control fm'])->label(false);?>
                </td>

<!--                <td>-->
<!--                    <p>状态</p>-->
<!--                    --><?php // echo $form->field($searchModel, 'status')->dropDownList(['0'=>'未签约','1'=>'已签约','2'=>'签约失败'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <p>所属省</p>-->
<!--                    --><?php // echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area fm'])->label(false) ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p>所属市</p>-->
<!--                    --><?php // echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area fm'])->label(false) ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p>所属区</p>-->
<!--                    --><?php // echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area fm'])->label(false) ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p>所属街道</p>-->
<!--                    --><?php // echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p>推荐时间</p>-->
<!--                    --><?//= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p> .</p>-->
<!--                    --><?//= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
<!--                </td>-->
<!--                <td>-->
<!--                    <p>业务合作人办事处</p>-->
<!--                    --><?php //echo $form->field($searchModel, '')->textInput(['class'=>'form-control fm'])->label(false);?>
<!--                </td>-->
                <td colspan="4">
                    <p></p>
                    <br/>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
</div>
