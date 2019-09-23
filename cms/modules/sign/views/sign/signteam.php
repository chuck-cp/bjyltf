<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
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
                <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('团队ID');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'team_name')->textInput(['class'=>'form-control fm'])->label('团队名称');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'team_member_name')->textInput(['class'=>'form-control fm'])->label('管理员');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'first_sign_time')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('首次签到时间') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'team_type')->dropDownList(['1'=>'业务','2'=>'维护'],['prompt'=>'全部','class'=>'form-control fm'])->label('团队类型') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'team_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('成员数量') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'team_manager_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('负责人数量') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'sign_interval_time')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('签到间隔时间') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel, 'sign_qualified_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','class'=>'form-control fm'])->label('签到达标数') ?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('创建时间');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false);?>
            </div>
            <div class="col-xs-2 form-group">
                <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
               <!-- --><?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'team_name',
            [
                'label' => '团队类型',
                'value' => function($searchModel){
                    return $searchModel->team_type == 1?'业务':'维护';
                }
            ],
            'team_member_name',
            'team_member_number',
            'team_manager_number',
            'first_sign_time',
            'sign_interval_time',
            'sign_qualified_number',
            'create_at',
            [
                'label' => '最早签退时间',
                'value' => function($searchModel){
                    return $searchModel->earliest_closing_time;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{teams}  {setting}',
                'buttons' => [
                    'teams' => function($url,$searchModel){
                        return Html::a('查看详情',['/sign/sign/teams','team_id'=>$searchModel->id]);
                    },
                    'setting' => function($url,$searchModel){
                        return Html::a('签到设置','javascript:void(0);',['team_id'=>$searchModel->id,'class'=>'setting']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.setting').on('click',function(){
        var team_id = $(this).attr('team_id');
        var pageup = layer.open({
            type: 2,
            title: '签到设置',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '50%'],
            content: '<?=\yii\helpers\Url::to(['/sign/sign/setting'])?>&team_id='+team_id
        });
    })
</script>