<?php

use yii\helpers\Html;
use common\libs\ToolsClass;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '第二月每月买断费支出';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-apply-brokerage">
    <div class="shop-apply-brokerage-search">
        <?php $form = ActiveForm::begin([
//            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">
            <table class="grid table table-striped table-bordered search">
                <tr>
                    <td>
                        <p>商家编号</p>
                        <?=$form->field($searchModel,'shop_id')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                    </td>
                    <td>
                        <p>商家名称</p>
                        <?=$form->field($searchModel,'shop_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                    </td>
                    <td>
                        <p>法人ID</p>
                        <?=$form->field($searchModel,'apply_id')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                    </td>
                    <td>
                        <p>法人姓名</p>
                        <?=$form->field($searchModel,'apply_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                    </td>
                    <td>
                        <p>法人手机号</p>
                        <?=$form->field($searchModel,'apply_mobile')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                    </td>
                    <td class="date">
                        <p>收款时间</p>
                        <?=$form->field($searchModel,'create_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                    </td>
                    <td class="date">
                        <?=$form->field($searchModel,'create_at_end')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>所属省</p>
                        <?php  echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area fm'])->label(false) ?>
                    </td>
                    <td>
                        <p>所属市</p>
                        <?php  echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area fm'])->label(false) ?>
                    </td>
                    <td>
                        <p>所属区</p>
                        <?php  echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area fm'])->label(false) ?>
                    </td>
                    <td>
                        <p>所属街道</p>
                        <?php  echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                    </td>
                    <td style="padding-top: 35px;" colspan="3">
                        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                        <?= Html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
                'firstPageLabel'=>'首页',
                'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            'id',
            'shop_id',
            'shop_name',
            'area_name',
            'address',
            'apply_id',
            'apply_name',
            'apply_mobile',
            [
                'label' => '维护费用时间周期',
                'value' => function($searchModel){
                    return substr($searchModel->date,0,4).'年'.substr($searchModel->date,-2).'月';
//                    return date('Y年m月',strtotime($searchModel->date));
                }
            ],
            'screen_number',
            'mirror_number',
            'install_finish_at',
            [
                'label' => '维护费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->price);
                }
            ],
        ],
    ]); ?>
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
