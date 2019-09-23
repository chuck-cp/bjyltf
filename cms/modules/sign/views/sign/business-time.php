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
                <p><b style="font-size: 20px;"><?=Html::encode($stat['total_sign_shop_number'])?></b>次</p>
                <p>签到总次数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['no_sign_member_number'])?></b>人</p>
                <p>未签到成员总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['overtime_sign_member_number'])?></b>次</p>
                <p>超时签到总次数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['unqualified_member_number'])?></b>人</p>
                <p>未达标成员总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['leave_early_number'])?></b>人</p>
                <p>早退成员总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['repeat_sign_number'])?></b>次</p>
                <p>重复签到总数</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['repeat_sign_rate'])?>%</b></p>
                <p>签到重复率</p>
            </td>
            <td>
                <p><b style="font-size: 20px;"><?=Html::encode($stat['repeat_shop_number'])?></b>家</p>
                <p>重复店铺总数</p>
            </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class'=>'yii\grid\SerialColumn'],
            'create_at',
            'total_sign_shop_number',
            'total_sign_member_number',
            'no_sign_member_number',
            'overtime_sign_member_number',
            'unqualified_member_number',
            'leave_early_number',
            'repeat_sign_number',
            [
                'label' => '签到重复率',
                'value' => function($searchModel){
                    return $searchModel->repeat_sign_rate.'%';
                }
            ],
            'repeat_shop_number',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/sign/sign/business-time-list','date'=>$searchModel->create_at]);
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
