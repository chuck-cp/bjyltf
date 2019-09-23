<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;

$this->title = '内部业务人员排行';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="member-index">
    <div class="member-search">

        <?php $form = ActiveForm::begin([
//            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <input type="hidden" name="type" value="1">
        <div class="row">
            <div class="col-xs-2 form-group">
                <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label('时间区间'); ?>
            </div>
            <div class="col-xs-2 form-group">
                <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('电话');?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','data'=>'provience','key'=>'area','class'=>'form-control fm'])->label('所属省') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','data'=>'city','key'=>'area','class'=>'form-control fm'])->label('所属市') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','data'=>'area','key'=>'area','class'=>'form-control fm'])->label('所属区') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','data'=>'town','key'=>'area','class'=>'form-control fm'])->label('所属街道') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'member_id',
            [
                'label' => '姓名',
                'value' => function($model){
                    return $model->member['name'];
                }
            ],
            [
                'label' => '联系电话',
                'value' => function($model){
                    return $model->member['mobile'];
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($model){
                    return $model->member['area_name'];
                }
            ],
            [
                'label' => '业务区域',
                'value' => function($model){
                    return SystemAddress::getAreaByIdLen($model->member['admin_area'],9);
                }
            ],
            [
                'label' => '已安装商家数量',
                'value' => function($model){
                    return $model->totalshop ? $model->totalshop : 0;
                }
            ],
            [
                'label' => '已安装LED数量',
                'value' => function($model){
                    return $model->totalscreen ? $model->totalscreen : 0;
                }
            ],
            [
                'label' => '待安装商家数量',
                'value' => function($model){
                    return $model->memberShopApplyRank['wait_install_shop_number'] ? $model->memberShopApplyRank['wait_install_shop_number'] : 0;
                }
            ],
            [
                'label' => '待安装LED数量',
                'value' => function($model){
                    return $model->memberShopApplyRank['wait_install_screen_number'] ? $model->memberShopApplyRank['wait_install_screen_number'] : 0;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                        'view' => function($url,$model){
                            return Html::a('查看详情',['member/view','id'=>$model->member_id]);
                        },
                ],
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .fm{width: 105px;display: inline-block;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$(function () {
    //点击切换地区
    $("select[key='area']").change(function () {
        var parent_id = $(this).val();
        var type = $(this).attr('data');
        var selObj = $(this).parents('.col-xs-2');
        selObj.nextAll().find('select').find('option:not(:first)').remove();
        if (!parent_id) {
            return false;
        }
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
            type: 'POST',
            dataType: 'json',
            data: {'parent_id': parent_id},
            success: function (phpdata) {
                $.each(phpdata, function (i, item) {
                    selObj.next().find('select').append('<option value=' + i + '>' + item + '</option>');
                })
            }, error: function (phpdata) {
                layer.msg('获取失败！');
            }
        })
    })
})
</script>