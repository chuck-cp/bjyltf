<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use cms\modules\sign\models\SignTeam;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\sign\models\search\SignTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队管理日志';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="sign-team-index">
    <?php
    $form = ActiveForm::begin([
        'action' => ['sign-log'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>操作人</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'回访店铺','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>团队名称</p>
                    <?=$form->field($searchModel,'team_name')->textInput(['placeholder'=>'用户名','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>操作人职务</p>
                    <?php  echo $form->field($searchModel, 'member_type')->dropDownList(['1'=>'普通成员','2'=>'负责人','3'=>'管理人'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>团队类型</p>
                    <?php  echo $form->field($searchModel, 'team_type')->dropDownList(['1'=>'业务团队','2'=>'维护团队'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>操作时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p> .</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td >
                    <p></p>
                    <br/>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '操作时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '操作人',
                'value' => function($searchModel){
                    return $searchModel->member_name;
                }
            ],
            [
                'label' => '操作职务',
                'value' => function($searchModel){
                    if($searchModel->member_type==1){
                        return '普通成员';
                    }elseif($searchModel->member_type==2){
                        return '负责人';
                    }elseif($searchModel->member_type==3){
                        return '管理人';
                    }else{
                        return '--';
                    }
                }
            ],
            [
                'label' => '操作团队名称',
                'value' => function($searchModel){
                    return $searchModel->team_name;
                }
            ],
            [
                'label' => '团队类型',
                'value' => function($searchModel){
                    return $searchModel->team_type==1?'业务团队':'维护团队';
                }
            ],
            [
                'label' => '操作内容',
                'value' => function($searchModel){
                    return $searchModel->content;
                }
            ],
        ],
    ]); ?>
</div>

