<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use cms\modules\member\models\MemberInfo;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\withdraw\models\search\MemberWithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '等待出纳';
$this->params['breadcrumbs'][] = ['label' => '提现管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('static/js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css');
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $form = ActiveForm::begin([
    'action' => ['cashier'],
    'method' => 'get',
]); ?>
<input type="hidden" name="page" value="cashier">
<div class="row">
    <div class="col-xs-2">
        <label for="">申请时间：</label>
        <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'申请开始时间','class'=>'form-control datepicker start'])->label(false); ?>
        <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'申请结束时间','class'=>'form-control datepicker start'])->label(false); ?>
    </div>

    <div class="col-xs-2">
        <label for="">姓名：</label>
        <?= $form->field($searchModel, 'member_name')->textInput(['class'=>'form-control','placeholder'=>'请输入姓名'])->label(false); ?>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
            <?= Html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
        </div>
    </div>
    <div class="col-xs-2">
        <label for="">手机号：</label>
        <?= $form->field($searchModel, 'mobile')->textInput(['class'=>'form-control','placeholder'=>'请输入手机号'])->label(false); ?>
    </div>
</div>
<? ActiveForm::end();?>
<?= Html::a('批量提现成功','javascript:;',['class' => 'btn btn-primary pltg']); ?>
<div class="member-withdraw-index" style="margin-top: 10px;">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'serial_number',
            'create_at',
            'member_id',
            'member_name',
            'mobile',
            'bank_name',
            'payee_name',
            [
                'label' => '身份证',
                'value' => function($searchModel){
                    return MemberInfo::getIdInfoByMemberId($searchModel->member_id,'id_number');
                }
            ],
            [
                'label'=>'账户类型',
                'value' => function($searchModel){
                    return $searchModel->account_type == 1 ? '个人' : '公司';
                }
            ],
            'bank_account',
            'bank_mobile',
            [
                'label' => '提现状态',
                'value' => function($searchModel){
                    return $searchModel->status == 0 ? '未提现' : '待提现';
                }
            ],
            [
                'label' => '提现金额',
                'value' => function($searchModel){
                    return number_format($searchModel->price/100,2);
                }
            ],
            [
                'label' => '手续费',
                'value' => function($searchModel){
                    return number_format($searchModel->poundage/100,2);
                }
            ],
            [
                'label' => '账户余额',
                'value' => function($searchModel){
                    return number_format($searchModel->account_balance/100,2);
                }
            ],
            [
                'label' => '审核状态',
                'value' => function($searchModel){
                    return \cms\modules\withdraw\models\MemberWithdraw::getExaimneStatus($searchModel->examine_status,$searchModel->examine_result);
                }
            ],
            [
                    'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{pass}',
                'buttons' => [
                    'pass' => function($url,$searchModel){
                        return html::a('提现结果','javascript:;',['data'=>$searchModel->id,'class'=>'examine','type'=>'pass']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .fm{with:110px;!important;display: inline-block;}
    #t1,#t2{width: 110px;}
    .action-column{width:80px;}
</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        //审核
        $('.examine').bind('click',function () {
            var __this = $(this);
            var id = $(this).attr('data');
            var type = $(this).attr('type');
            var url_route = '<?=\yii\helpers\Url::to(['cash-examine'])?>';
            if(type == 'pass'){
                //询问框
                layer.confirm('您确定提现成功了吗？',{
                    btn: ['成功','失败'] //按钮
                }, function(){
                    $.ajax({
                        url: url_route,
                        type: 'POST',
                        dataType: 'json',
                        data:{'id':id,'type':'pass','page':'cashier'},
                        success:function (phpdata) {
                            if(phpdata){
                                if(phpdata == 1){
                                    layer.msg('操作成功！');
                                    //__this.parents('td').prev().html('通过');
                                    location.reload();
                                }else if(phpdata == 5){
                                    layer.msg('您已经审核，请勿重复审核！');
                                }
                            }else{
                                layer.msg('操作失败！');
                            }
                        },error:function (phpdata) {
                            layer.msg('操作失败！');
                        }
                    })
                }, function(){
                    $.ajax({
                        url: url_route,
                        type: 'POST',
                        dataType: 'json',
                        data:{'id':id,'type':'rebut','page':'cashier'},
                        success:function (phpdata) {
                            if(phpdata){
                                if(phpdata == 1){
                                    layer.msg('操作成功！');
                                    __this.parents('td').prev().html('通过');
                                }else if(phpdata == 5){
                                    layer.msg('您已经审核，请勿重复审核！');
                                }
                            }else{
                                layer.msg('操作失败！');
                            }
                        },error:function (phpdata) {
                            layer.msg('操作失败！');
                        }
                    })
                });
            }

        })

        //批量通过
        $(".pltg").click(function(){
            var obj = document.getElementsByName("selection[]");//选择所有name="selection[]"的对象，返回数组
            var ids='';//如果这样定义var s;变量s中会默认被赋个null值
            for(var i=0;i<obj.length;i++){
                if(obj[i].checked) //取到对象数组后，我们来循环检测它是不是被选中
                    ids+=obj[i].value+',';  //如果选中，将value添加到变量s中
            }
            if(!ids){
                layer.msg('至少选择一项',{icon:7})
            }
            layer.confirm('您确定全部提现成功了吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['batch-cash-examine'])?>',
                    type: 'POST',
                    dataType: 'json',
                    data:{'ids':ids},
                    success:function (phpdata) {
                        if(phpdata.code==1){
                            layer.msg(phpdata.msg,{icon:1});
                            location.reload()
                        }else{
                            layer.msg(phpdata.msg,{icon:2})
                        }
                    },error:function (phpdata) {
                        layer.msg('操作失败！');
                    }
                })
            }, function(){

            });

        });
        //日期判断
//        $("#w0").on("submit",function(event){
//           $('.start').each(function () {
//                alert($(this).attr('placeholder'));
//           });
//            return false;
//            //event.preventDefault();
//        })


    })
</script>