<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use cms\modules\member\models\MemberInfo;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\withdraw\models\search\MemberWithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '等待财务';
$this->params['breadcrumbs'][] = ['label' => '提现管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('static/js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css');
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $form = ActiveForm::begin([
    'action' => ['withdraw'],
    'method' => 'get',
]); ?>
<input type="hidden" name="page" value="withdraw">
<div class="row">
    <div class="col-xs-2">
        <label for="">申请时间：</label>
        <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'申请开始时间','class'=>'form-control datepicker start'])->label(false); ?>
        <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'申请结束时间','class'=>'form-control datepicker start'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">提现时间：</label>
        <?= $form->field($searchModel, 'update_at')->textInput(['placeholder'=>'提现开始时间','class'=>'form-control datepicker'])->label(false); ?>
        <?= $form->field($searchModel, 'update_at_end')->textInput(['placeholder'=>'提现结束时间','class'=>'form-control datepicker'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">请选择：</label>
        <?= $form->field($searchModel, 'serial_number')->dropDownList(['1'=>'提现编号','2'=>'收款人银行','3'=>'收款人姓名','4'=>'银行预留电话'],['prompt'=>'全部'])->label(false); ?>
        <?= $form->field($searchModel, 'payee_name')->textInput(['class'=>'form-control'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">提现人：</label>
        <?= $form->field($searchModel, 'member_name')->textInput(['class'=>'form-control','placeholder'=>'请输入姓名'])->label(false); ?>
        <?= $form->field($searchModel, 'mobile')->textInput(['class'=>'form-control','placeholder'=>'请输入手机号'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">审核状态：</label>
        <?= $form->field($searchModel, 'examine_result')->dropDownList(['2'=>'提现成功','1'=>'提现失败'],['prompt'=>'全部'])->label(false); ?>
    </div>
    <div class="col-xs-2">
        <label for="">账户类型：</label>
        <?= $form->field($searchModel, 'account_type')->dropDownList(['1'=>'个人','2'=>'公司'],['prompt'=>'全部'])->label(false); ?>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
            <?= Html::submitButton('导出',['class' => 'btn btn-primary', 'name'=>'search', 'value'=>0]); ?>
        </div>
    </div>
</div>
<? ActiveForm::end();?>

<div class="member-withdraw-index">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
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
                    return $searchModel->memberinfo['id_number'];
                    /*if($searchModel->memberinfo['id_number']){
                        return $searchModel->memberinfo['id_number'];
                    }else{
                        return '---';
                    }*/
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
                    return $searchModel->examine_status == 3 && $searchModel->examine_result == 1 ? '提现失败' : '提现成功';
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
//            [
//                'label' => '审核状态',
//                'value' => function($searchModel){
//                    return \cms\modules\withdraw\models\MemberWithdraw::getExaimneStatus($searchModel->examine_status,$searchModel->examine_result);
//                }
//            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{pass} {rebut} {view}',
//                'buttons' => [
//                    'pass' => function($url,$searchModel){
//                        return html::a('通过','javascript:;',['data'=>$searchModel->id,'class'=>'examine','type'=>'pass']);
//                    },
//                    'rebut' => function($url,$searchModel){
//                        return html::a('驳回','javascript:;',['data'=>$searchModel->id,'class'=>'examine','type'=>'rebut']);
//                    },
//                    'view' => function($url,$searchModel){
//                        return html::a('查看','javascript:;',['data'=>$searchModel->id,'class'=>'examine','type'=>'detail']);
//                    },
//                ],
//            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .fm{with:110px;!important;display: inline-block;}
    #t1,#t2{width: 110px;}
</style>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
</script>