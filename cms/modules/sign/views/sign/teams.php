<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\modules\sign\models\SignTeamMember;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');

$this->title = '团队管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sign-team-index">

    <div class="sign-team-search">
        <?php $form = ActiveForm::begin([
//        'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="row">
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('用户名');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('联系电话');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'member_type')->dropDownList(['0'=>'管理员','1'=>'负责人','2'=>'成员'],['prompt'=>'全部','class'=>'form-control fm'])->label('职务');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'sign_numbers')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('签到次数') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'late_signs')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('超时签到次数') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('签到日期');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false);?>
            </div>
            <div class="col-xs-2 form-group">
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
              <!--  --><?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'member_id',
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
                'label' => '职务',
                'value' => function($searchModel){
                    return SignTeamMember::getDuthByType($searchModel->member_type);
                }
            ],
            [
                'label' => '签到次数',
                'value' => function($searchModel){
                    return $searchModel->sign_numbers;
                }
            ],
            [
                'label' => '超时签到次数',
                'value' => function($searchModel){
                    return $searchModel->late_signs;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel) use ($date){
                        return Html::a('查看详情',['/sign/sign/member-date','team_id'=>$searchModel->team_id,'member_id'=>$searchModel->member_id,'startdate'=>$date['start'],'enddate'=>$date['end']]);//
                    },
                ],
            ],
        ],
    ]); ?>
</div>
