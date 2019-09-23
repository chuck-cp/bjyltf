<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use cms\modules\sign\models\SignTeam;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\sign\models\search\SignTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队管理';
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
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>所属团队</p>
                    <?php  echo $form->field($searchModel, 'team_name')->dropDownList(SignTeam::signTeam(1),['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>签到总数</p>
                    <?php  echo $form->field($searchModel, 'total_sign_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>未签到数</p>
                    <?php  echo $form->field($searchModel, 'no_sign_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>重复签到数</p>
                    <?php  echo $form->field($searchModel, 'repeat_sign_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>团队成员数</p>
                    <?php  echo $form->field($searchModel, 'team_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>未达标数</p>
                    <?php  echo $form->field($searchModel, 'unqualified_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>超时签到数</p>
                    <?php  echo $form->field($searchModel, 'overtime_sign_member_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>早退成员数</p>
                    <?php  echo $form->field($searchModel, 'leave_early_number')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <!--<td>
                    <p>店铺重复率</p>
                    <?php /* echo $form->field($searchModel, 'repeat_sign_rate')->dropDownList(['1'=>'由高到低','2'=>'由低到高'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) */?>
                </td>-->
                <td>
                    <p>开始时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>

                </td>
                <td>
                    <p>结束时间</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p>.</p>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class'=>'yii\grid\SerialColumn'],
            [
                'label' => '所属团队',
                'value' => function($searchModel){
                    return $searchModel->signTeam['team_name'];
                }
            ],
            [
                'label' => '团队成员数',
                'value' => function($searchModel){
                    return $searchModel->signTeam['team_member_number'];
                }
            ],
            [
                'label' => '签到总数',
                'value' => function($model){
                    return $model->total_sign_shop_number_sum;
                }
            ],
            [
                'label' => '未签到成员数',
                'value' => function($model){
                    return $model->no_sign_member_number_sum;
                }
            ],
            [
                'label' => '超时签到数',
                'value' => function($model){
                    return $model->overtime_sign_member_number_sum;
                }
            ],
            [
                'label' => '未达标成员数',
                'value' => function($model){
                    return $model->unqualified_member_number_sum;
                }
            ],
            [
                'label' => '早退成员数',
                'value' => function($model){
                    return $model->leave_early_number_sum;
                }
            ],
            [
                'label' => '重复签到数',
                'value' => function($model){
                    return $model->repeat_sign_number_sum;
                }
            ],
            [
                'label' => '重复店铺数',
                'value' => function($model){
                    return $model->repeat_shop_number_sum;
                }
            ],
            [
                'label' => '重复签到率',
                'value' => function($model){
                    if($model->total_sign_shop_number_sum!=0){
                        $repeat_sign_rate = round($model->repeat_sign_number_sum/$model->total_sign_shop_number_sum,'4')*100;
                        return $repeat_sign_rate.'%';
                    }else{
                        return '0.00%';
                    }


                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url,$searchModel) use ($create_at,$create_at_end){
                       return Html::a('查看详情',['/sign/sign/business-team-view','create_at'=>$create_at,'create_at_end'=>$create_at_end,'team_id'=>$searchModel->team_id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
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
