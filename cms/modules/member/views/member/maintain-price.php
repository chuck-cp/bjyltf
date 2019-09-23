<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每月维护费用支出';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="screen-run-time-shop-subsidy-index">
    <?php /* echo $this->render('_search', ['model' => $searchModel]); */?>
    <?php $form = ActiveForm::begin([
        'action' => ['maintain-price'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>店铺ID</p>
                    <?=$form->field($searchModel,'shop_id')->textInput(['class'=>'form-control collection-width fm','placeholder'=>'店铺ID'])->label(false);?>
                </td>
                <td>
                    <p>商家名称</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td class="date">
                    <p>收款时间</p>
                    <?=$form->field($searchModel,'create_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                </td>
                <td class="date">
                    <?=$form->field($searchModel,'create_at_end')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
                </td>
                <td>
                    <p>法人姓名</p>
                    <?=$form->field($searchModel,'apply_name')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>
                <td>
                    <p>法人账号</p>
                    <?=$form->field($searchModel,'apply_mobile')->textInput(['class' => 'form-control collection-width fm'])->label(false); ?>
                </td>

                <td>
                    <p>维护费用异常</p>
                    <?=$form->field($searchModel, 'abnormal')->dropDownList(['1'=>'正常','2'=>'异常'],['prompt'=>'全部','class'=>'form-control fm'])->label(false) ?>
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

                <td colspan="3" style="padding-top: 35px;">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                    <?=  html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
    <table style="border: 1px solid #dddddd; width: 19%; margin-bottom: 20px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    支出总额：<?echo $TotalPrice?>
                </p>
            </th>
        </tr>
    </table>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '序号',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '商家编号',
                'value' => function($searchModel){
                    return $searchModel->shop_id;
                }
            ],
            [
                'label' => '商家名称',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return $searchModel->area_name;
                }
            ],
            [
                'label' => '法人姓名',
                'value' => function($searchModel){
                    return $searchModel->apply_name;
                }
            ],
            [
                'label' => '法人手机号',
                'value' => function($searchModel){
                    return $searchModel->apply_mobile;
                }
            ],
            [
                'label' => '维护费用时间周期',
                'value' => function($searchModel){
                    return substr($searchModel->date,0,4).'年'.substr($searchModel->date,-2).'月';
                   //var_dump(str_split($searchModel->date,4));
                }
            ],
            [
                'label' => '屏幕数量',
                'value' => function($searchModel){
                    return $searchModel->screen_number;
                }
            ],
            [
                'label' => '应发维护费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->reduce_price);
                }
            ],
            [
                'label' => '实发维护费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->price);
                }
            ],
            [
                'label' => '是否发放',
                'value' => function($searchModel){
                    return $searchModel->status==1?'发放':'不发放';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '明细详情',
                'template' => '{detailed}',
                'buttons' => [
                    'detailed' => function($url,$searchModel){
                        return html::a('查看费用明细','javascript:void(0);',['class'=>'view','shop_id'=>$searchModel->shop_id,'date'=>$searchModel->date]);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{status}  &nbsp;&nbsp;  {upprice}',
                'buttons' => [
                    'status'=>function($url,$searchModel){
                        if($searchModel->grant_status==1){
                            return '费用已发放';
                        }
                        else{
                            if($searchModel->status==1){
                                return html::a('本月不发放','javascript:void(0);',['class'=>'status','id'=>$searchModel->id,'status'=>$searchModel->status]);
                            }else{
                                return html::a('本月发放','javascript:void(0);',['class'=>'status','id'=>$searchModel->id,'status'=>$searchModel->status]);
                            }
                        }
                    },
                    'upprice'=>function($url,$searchModel){
                        if($searchModel->grant_status==0){
                            return html::a('修改费用','javascript:void(0);',['class'=>'upprice','shop_id'=>$searchModel->shop_id,'date'=>$searchModel->date]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<style>
    .cz{color:#7d7e70;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.view').click(function () {
        var shop_id = $(this).attr('shop_id');
        var date = $(this).attr('date');

        var pageup = layer.open({
            type: 2,
            title: '查看费用明细',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/member/member/maintain-price-view'])?>&shop_id='+shop_id+'&date='+date
        });
    })
    $('.upprice').click(function(){
        var shop_id = $(this).attr('shop_id');
        var date = $(this).attr('date');
        var pageup = layer.open({
            type: 2,
            title: '修改维护费用',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/member/member/maintain-price-up'])?>&shop_id='+shop_id+'&date='+date
        });
    })
    $('.status').click(function(){
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['maintain-price-status'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'id':id,'status':status},
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
    //获取地址
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
