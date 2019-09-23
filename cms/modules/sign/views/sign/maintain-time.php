<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use cms\modules\sign\models\SignTeam;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\sign\models\search\SignTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '按时间维护签到统计';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="sign-team-index">
    <?php
    $form = ActiveForm::begin([
//        'action' => ['sign-business'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label('签到时间'); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
            <?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
        </div>
    </div>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr style="text-align: center;">
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['total_sign_number'])?></b>次</p>
                <p>签到总次数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['no_sign_member_number'])?></b>次</p>
                <p>未签到成员总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['overtime_sign_member_number'])?></b>次</p>
                <p>超时签到总次数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['unqualified_member_number'])?></b>次</p>
                <p>未达标成员总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['leave_early_number'])?></b>次</p>
                <p>早退成员数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['good_evaluate_number'])?></b>次</p>
                <p>好评总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['middle_evaluate_number'])?>次</b></p>
                <p>中评总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['bad_evaluate_number'])?></b>次</p>
                <p>差评总数</p>
            </td>
                <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['bad_evaluate_rate'])?></b>%</p>
                <p>差评率</p>
            </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class'=>'yii\grid\SerialColumn'],
            'id',
            'create_at',
            'total_sign_number',
            'total_sign_member_number',
            'no_sign_member_number',
            'overtime_sign_member_number',
            'unqualified_member_number',
            'leave_early_number',
            'good_evaluate_number',
            'middle_evaluate_number',
            'bad_evaluate_number',
            [
                'label' => '差评率',
                'value' => function($searchModel){
                    return $searchModel->bad_evaluate_rate.'%';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/sign/sign/maintain-time-list','date'=>$searchModel->create_at]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

