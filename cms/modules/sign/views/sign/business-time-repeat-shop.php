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
    <?php echo $this->render('layout/business',['searchModel'=>$searchModel]);?>
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
                    <p>拜访店铺</p>
                    <?=$form->field($searchModel,'shop_name')->textInput(['placeholder'=>'拜访店铺','class'=>'form-control fm'])->label(false);?>
                </td>
                <!--<td>
                    <p>用户名</p>
                    <?/*=$form->field($searchModel,'member_name')->textInput(['placeholder'=>'编号','class'=>'form-control fm'])->label(false);*/?>
                </td>-->
                <td>
                    <p>店铺类型</p>
                    <?php  echo $form->field($searchModel, 'shop_type')->dropDownList(['1'=>'租赁','2'=>'自营','3'=>'连锁'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
                </td>
                <!--<td>
                    <p>所属团队</p>
                    <?php /* echo $form->field($searchModel, 'team_name')->dropDownList(SignTeam::signTeam(),['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) */?>
                </td>-->
                <td>
                    <p>签到地址</p>
                    <?=$form->field($searchModel,'shop_address')->textInput(['placeholder'=>'签到地址','class'=>'form-control fm'])->label(false);?>
                </td>
                <td>
                    <p>有无屏幕</p>
                    <?php  echo $form->field($searchModel, 'screen')->dropDownList(['2'=>'无','1'=>'有'],['prompt'=>'全部','key'=>'province','class'=>'form-control fm'])->label(false) ?>
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
                <td colspan="4">
                    <p></p>
                    <br/>
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
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
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
                    return $searchModel->signBusiness['bd_longitude'];
                }
            ],
            [
                'label' => '维度',
                'value' => function($searchModel){
                    return $searchModel->signBusiness['bd_latitude'];
                }
            ],
            [
                'label' => '店铺类型',
                'value' => function($searchModel){
                    if($searchModel->signBusiness['shop_type']==1){
                        return  '租赁';
                    }else if($searchModel->signBusiness['shop_type']==2){
                        return  '自营';
                    }else if($searchModel->signBusiness['shop_type']==3){
                        return  '连锁';
                    }
                }
            ],
            [
                'label' => '重复数量',
                'value' => function($model){
                    return $model->totalmongo_id;
                }
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
