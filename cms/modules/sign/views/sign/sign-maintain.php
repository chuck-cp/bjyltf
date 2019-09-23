<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\models\SystemAddress;
use cms\modules\sign\models\SignTeam;
use cms\models\LoginForm;
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
        'action' => ['sign-maintain'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>回访店铺</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['placeholder'=>'回访店铺','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>用户名</p>
                    <?=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'用户名','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>签到地址</p>
                    <?=$form->field($searchModel,'shop_address')->textInput(['placeholder'=>'签到地址','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>店铺类型</p>
                    <?php  echo $form->field($searchModel, 'shop_type')->dropDownList(['1'=>'租赁','2'=>'自营','3'=>'连锁'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属团队</p>
                    <?php  echo $form->field($searchModel, 'team_id')->dropDownList(SignTeam::signTeam(2,$sign_team),['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>评分</p>
                    <?php  echo $form->field($searchModel, 'evaluate')->dropDownList(['1'=>'好评','2'=>'中评','3'=>'差评','0'=>'未评价'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td>
                    <p>签到时间</p>
                    <?= $form->field($searchModel, 'create_at')->textInput(['placeholder'=>'开始时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
                <td>
                    <p> .</p>
                    <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control datepicker fm'])->label(false); ?>
                </td>
            </tr>
            <tr>

                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control area fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control area fm'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control area fm'])->label(false) ?>
                </td>
               <!-- <td>
                    <p>所属街道</p>
                    <?php /* echo $form->field($searchModel, 'town')->dropDownList(SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) */?>
                </td>-->
                <td colspan="4">
                    <p></p>
                    <br/>
                    <?=Html::submitButton('搜索',['class'=>'btn btn-primary','name'=>'search','value'=>1])?>
                    <?/*=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])*/?>
                    <?if(LoginForm::checkPermission('/export/power/sign_maintain_export')):?>
                        <?=Html::submitButton('导出',['class'=>'btn btn-primary','name'=>'search','value'=>0])?>
                    <?endif;?>
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
                'label' => '签到时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '拜访店铺',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '签到位置',
                'value' => function($searchModel){
                    return $searchModel->shop_address;
                }
            ],
            [
                'label' => '经度',
                'value' => function($searchModel){
                    return $searchModel->signMaintain['bd_longitude'];
                }
            ],
            [
                'label' => '维度',
                'value' => function($searchModel){
                    return $searchModel->signMaintain['bd_latitude'];
                }
            ],
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    if($searchModel->signMaintain['shop_type']==1){
                        return  '租赁';
                    }else if($searchModel->signMaintain['shop_type']==2){
                        return  '自营';
                    }else if($searchModel->signMaintain['shop_type']==3){
                        return  '连锁';
                    }
                }
            ],
            [
                'label' => '用户名',
                'value' => function($searchModel){
                    return $searchModel->member_name;
                }
            ],
            [
                'label' => '所属团队',
                'value' => function($searchModel){
                    return $searchModel->team_name;
                }
            ],
            [
                'label' => '是否首次签到',
                'value' => function($searchModel){
                    return $searchModel->first_sign==1?'是':'否';
                }
            ],
            [
                'label' => '评分',
                'value' => function($searchModel){
                    if($searchModel->signMaintain['evaluate']==1){
                        return  '好评';
                    }else if($searchModel->signMaintain['evaluate']==2){
                        return  '中评';
                    }else if($searchModel->signMaintain['evaluate']==3){
                        return  '差评';
                    }else{
                        return '暂无评价';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/sign/sign/sign-maintain-view','id'=>$searchModel->id]);
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
