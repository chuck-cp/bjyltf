<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\shop\models\search\BuildingShopFloorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-shop-floor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['poster-index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>楼宇ID</p>
                    <?=$form->field($searchModel,'id')->textInput(['placeholder'=>'楼宇ID','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>楼宇名称</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['placeholder'=>'楼宇名称','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>公司名称</p>
                    <?=$form->field($searchModel,'company_name')->textInput(['placeholder'=>'公司名称','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>联系人姓名</p>
                    <?=$form->field($searchModel,'contact_name')->textInput(['placeholder'=>'联系人姓名','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>联系人电话</p>
                    <?=$form->field($searchModel,'contact_mobile')->textInput(['placeholder'=>'联系人电话','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>申请人</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['placeholder'=>'申请人','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>楼宇类型</p>
                    <?=$form->field($searchModel,'floor_type')->dropDownList(['1'=>'写字楼','2'=>'商住两用'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>申请状态</p>
                    <?=$form->field($searchModel,'poster_examine_status')->dropDownList(['0'=>'申请待审核','1'=>'申请未通过','2'=>'待安装','3'=>'安装待审核','4'=>'安装未通过','5'=>'安装完成','6'=>'已关闭'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td>
                    <p>合同状态</p>
                    <?=$form->field($searchModel,'contract_status')->dropDownList(['1'=>'正常','2'=>'作废'],['class'=>'form-control fm','prompt'=>'全部'])->label(false);?>
                </td>
                <td colspan="">
                    <p>审核通过时间</p>
                    <?= $form->field($searchModel, 'poster_examine_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td colspan="">
                    <p>.</p>
                    <?= $form->field($searchModel, 'poster_examine_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
            </tr>
            <tr>

                <td colspan="">
                    <p>楼宇安装完成时间</p>
                    <?= $form->field($searchModel, 'poster_install_finish_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td colspan="">
                    <p>.</p>
                    <?= $form->field($searchModel, 'poster_install_finish_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td colspan="">
                    <p>合同审核通过时间</p>
                    <?= $form->field($searchModel, 'contract_examine_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label(false);?>
                </td>
                <td colspan="">
                    <p>.</p>
                    <?= $form->field($searchModel, 'contract_examine_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
                </td>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td style="padding-top: 30px;">
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
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