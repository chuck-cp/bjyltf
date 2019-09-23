<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\member\models\MemberInfo;
$this->title = '安装人员审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['installer'],
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('姓名');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'mobile')->textInput(['class'=>'form-control fm'])->label('手机号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'electrician_examine_status')->dropDownList(['0'=>'待审核','1'=>'审核通过','2'=>'已驳回'],['prompt'=>'全部','class'=>'form-control fm'])->label('审核状态'); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'id',
                'value' => function($searchModel){
                    return $searchModel->member_id;
                }
            ],
            [
                'label' => '姓名',
                'value' => function($searchModel){
                    return $searchModel->name;
                }
            ],
            [
                'label' => '性别',
                'value' => function($searchModel){
                    return $searchModel->sex==1?'男':'女';
                }
            ],
            [
                'label' => '联系电话',
                'value' => function($searchModel){
                    return $searchModel->member['mobile'];
                }
            ],
            [
                'label' => '身份证号',
                'value' => function($searchModel){
                    return $searchModel->id_number;
                }
            ],
            [
                'label' => '电工证编号',
                'value' => function($searchModel){
                    return $searchModel->electrician_certificate_number;
                }
            ],
            [
                'label' => '电工证类别',
                'value' => function($searchModel){
                    return $searchModel->electrician_certificate_type;
                }
            ],
            [
                'label' => '准操项目',
                'value' => function($searchModel){
                    return $searchModel->professional_name;
                }
            ],
            [
                 'label' => '申请时间',
                 'value' => function($searchModel){
                    return $searchModel->electrician_certificate_apply_at;
                 }
            ],
            [
                'label' => '是否为内部电工',
                'value' => function($searchModel){
                    return $searchModel->company_electrician==1?'是':'不是';
                }
            ],
            [
                'label' => '所属团队',
                'value' => function($searchModel){
                    return $searchModel->memTeam['team_name']?$searchModel->memTeam['team_name']:'';
                }
            ],
            [
                'label' => '状态',
                'value' => function($searchModel){
                    return MemberInfo::getMemberElectricianStatus($searchModel->electrician_examine_status);
                }
            ],

            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {edit}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看',['/examine/chef/installer-view','id'=>$searchModel->member_id]);
                    },
                    'edit' => function($url,$searchModel){
                        if($searchModel->company_electrician==1){
                            return html::a('取消内部电工','javascript:void(0);',['class'=>'status','id'=>$searchModel->member_id,'status'=>$searchModel->company_electrician]);
                        }else{
                            return html::a('设为内部电工','javascript:void(0);',['class'=>'status','id'=>$searchModel->member_id,'status'=>$searchModel->company_electrician]);
                        }
                    }
                ],
            ],
        ]

    ]);?>
    <?php ActiveForm::end();?>
</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $('.status').click(function(){
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        if(status==2){
            var title='设为内部电工';
            var Prompt='你确定要将此用户设置为内部电工吗？';
        }else{
            var title='取消内部电工';
            var Prompt='你确定要将此用户取消内部电工吗？'
        }
        layer.confirm(Prompt, {
            title:title,
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['isitofficial'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'status':status},
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！');
                }
            });
        });
    })

</script>