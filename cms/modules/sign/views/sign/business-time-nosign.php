<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use cms\modules\sign\models\SignTeam;
use cms\modules\sign\models\SignTeamMember;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\sign\models\search\SignTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队管理';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="sign-team-index">
    <?php echo $this->render('layout/business',['searchModel'=>$searchModel]);?>
    <?php
    $form = ActiveForm::begin([
        'action' => ['business-time-nosign'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>未签到用户名</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'姓名','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>联系电话</p>
                    <?=$form->field($searchModel, 'member_mobile')->textInput(['placeholder'=>'电话','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>所属团队</p>
                    <?php  echo $form->field($searchModel, 'team_id')->dropDownList(SignTeam::signTeam(1),['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>职务</p>
                    <div class="col-xs-2 form-group">
                        <?=$form->field($searchModel,'member_type')->dropDownList(['1'=>'普通成员','2'=>'负责人','3'=>'管理人'],['prompt'=>'全部','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>签到时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p>.</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p>.</p>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                   <!-- --><?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
                </td>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '未签时间',
                'value' => function($model){
                    return $model->create_at;
                }
            ],
            [
                'label' => '未签到用户名',
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
                'label' => '所属团队',
                'value' => function($model){
                    return $model->signTeam['team_name'];
                }
            ],
            [
                'label' => '职务',
                'value' => function($model){
                    return SignTeamMember::getDuthByType($model->memberType['member_type']);
                }
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">

</script>
