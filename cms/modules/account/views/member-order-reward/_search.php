<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="screen-run-time-shop-subsidy-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td class="date">
                    <p>时间</p>
                    <?=$form->field($model,'create_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                </td>
                <td class="date">
                    <?=$form->field($model,'create_at_end')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
                </td>
                <td>
                    <p>法人姓名</p>
                    <?=$form->field($model,'apply_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>法人账号</p>
                    <?=$form->field($model,'apply_mobile')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>商家名称</p>
                    <?=$form->field($model,'shop_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>

            </tr>
            <tr>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($model, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($model, 'city')->dropDownList(SystemAddress::getAreasByPid($model->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($model, 'area')->dropDownList(SystemAddress::getAreasByPid($model->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($model, 'town')->dropDownList(SystemAddress::getAreasByPid($model->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td style="padding-top: 35px;">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                    <?=  html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
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
