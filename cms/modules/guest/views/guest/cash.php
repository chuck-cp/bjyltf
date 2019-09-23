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
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $form = ActiveForm::begin([
    'action' => ['cash'],
    'method' => 'get',
]); ?>
<div class="row">
    <table class="grid table table-striped table-bordered search">
        <tr>
            <td>
                <p>申请时间</p>
                <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </td>
            <td>
                <p>.</p>
                <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </td>
            <td>
                <p>提现时间</p>
                <?= $form->field($searchModel, 'update_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </td>
            <td>
                <p>.</p>
                <?= $form->field($searchModel, 'update_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
            </td>
            <td>
                <p>提现编号</p>
                <?= $form->field($searchModel, 'serial_number')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
            <td>
                <p>收款人银行</p>
                <?= $form->field($searchModel, 'bank_name')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
        </tr>
        <tr>
            <td>
                <p>收款人姓名</p>
                <?= $form->field($searchModel, 'payee_name')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
            <td>
                <p>银行预留电话</p>
                <?= $form->field($searchModel, 'bank_mobile')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
            <td>
                <p>姓名</p>
                <?= $form->field($searchModel, 'member_name')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
            <td>
                <p>手机号</p>
                <?= $form->field($searchModel, 'mobile')->textInput(['class'=>'form-control fm'])->label(false); ?>
            </td>
            <td>
                <p>账户类型</p>
                <?= $form->field($searchModel, 'account_type')->dropDownList(['1'=>'个人','2'=>'公司'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
            </td>
            <td>
                <p>.</p>
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
            </td>
        </tr>
    </table>
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
                    if($searchModel->examine_status==0){
                        return '待审核';
                    }elseif ($searchModel->examine_status==1){
                        return '待审计';
                    }
                    elseif ($searchModel->examine_status==2){
                        return '待出纳';
                    }
                    elseif ($searchModel->examine_status==3){
                        return '出纳完成';
                    }
                }
            ],
            [
                'label' => '审核结果',
                'value' => function($searchModel){
                    if($searchModel->examine_status==3)
                        return $searchModel->examine_result==1?'提现失败':'提现成功';
                    return '---';
                }
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
    .fm{with:110px;!important;display: inline-block;}
    #t1,#t2{width: 110px;}
    .action-column{width:80px;}
</style>

