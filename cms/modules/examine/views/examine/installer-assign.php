<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\modules\shop\models\Shop;
use cms\modules\member\models\MemberAccount;
$this->title = '商家审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['installer-assign'],
        'method' => 'get',
    ]);
    ?>
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-xs-2');
                selObj.nextAll().find('select').find('option:not(:first)').remove();
                var parent_id = $(this).val();
                //alert(parent_id);
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

    <input type="hidden" name="id" value="<?=Html::encode($searchModel->id)?>">
    <?=$form->field($searchModel,'member_id')->hiddenInput(['value'=>$searchModel->id])->label(false);?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'apply_code')->textInput(['class'=>'form-control fm'])->label('订单号');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('商家编号');?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label('所属省') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label('所属市') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label('所属区') ?>
        </div>
        <div class="col-xs-2 form-group">
            <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label('所属街道') ?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'member_name')->textInput(['class'=>'form-control fm'])->label('业务合作人');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'apply_screen_number')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('申请数量');?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'create_at_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('创建时间'); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?= $form->field($searchModel, 'create_at_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false); ?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'assign_status')->dropDownList(['1'=>'已指派','2'=>'未指派'],['class'=>'form-control fm','prompt'=>'全部'])->label('是否指派');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'member_inside')->dropDownList(['1'=>'内部合作人','0'=>'外部合作人'],['class'=>'form-control fm','prompt'=>'全部'])->label('身份类别');?>
        </div>
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'install_member_name')->textInput(['class'=>'form-control fm'])->label('安装人姓名');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
            <?=  html::a('批量指派','javascript:;',['class' => 'btn btn-primary batch']); ?>
        </div>
    </div>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'member_name',
            [
                'label' => '身份类别',
                'value' => function($searchModel){
                    return $searchModel->member_inside == 1?'内部合作人':'外部合作人';
                }
            ],
            [
                'label' => '法人代表',
                'value' => function($searchModel){
                    return $searchModel->apply['apply_name'];
                }
            ],
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                }
            ],
            [
                'label' => '待安装屏幕数',
                'value' => function($searchModel){
                    return $searchModel->screen_number;
                }
            ],
            [
                'label' => '安装人姓名',
                'value' => function($searchModel){
                    return $searchModel->install_member_name;
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return Shop::getStatusByNum($searchModel->status);
                }
            ],
            'create_at',
            [
                'label' => '是否指派',
                'value' => function($searchModel){
                    if((int)$searchModel->install_team_id==0 && (int)$searchModel->install_member_id==0){
                        return '未指派';
                    }else{
                        return '已指派';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '标签',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel) use ($LableArr){
                        $labels = [];
                        foreach ($LableArr as $v){
                            foreach (explode(',',$searchModel->lable_id) as $vv){
                                if($v['id']==$vv){
                                    $labels[] = Html::a($v['title'],'javascript:void(0);',['title'=>$v['desc'],'shopid'=>$searchModel->id]);
                                }
                            }
                        }
                        return implode(',',$labels);
                    },
                ],
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {assign}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看',['/examine/examine/view','id'=>$searchModel->id, 'apply_name'=>$searchModel->apply['apply_name']]);
                    },
                    'assign' => function($url,$searchModel){
                        if($searchModel->install_team_id==0&&$searchModel->install_member_id==0){
                            return html::a('指派电工','javascript:void(0);',['class'=>'Assign','id'=>$searchModel->id]);
                        }else{
                            return html::a('取消指派','javascript:void(0);',['class'=>'qxzp','id'=>$searchModel->id]);
                        }

                    },

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
<script>
    //批量指派
    $('.batch').click(function(){
        var ids=$(':checkbox');
        var str='';
        var count=0;
        for(var i=0;i<ids.length;i++){
            if(ids.eq(i).is(':checked')){
                str+=','+ids.eq(i).val();
                count++;
            }
        }
        var str=str.substr(1);
        if(!str){
            layer.msg('至少选择一项',{icon:2});
            return false;
        }
        var ininfo=true;
        $('.table-bordered tr').each(function(){
            var selected=$(this).find('td').eq(0).find('input').prop('checked')
            if(selected){
                var wzp=$(this).find('td').eq(13).html();
                if(wzp=='已指派'){
                    layer.msg('已存在已指派的店铺，请勿重复选择',{icon:2});
                    ininfo=false;
                    return false;
                }
            }
        })
       if(ininfo){
           var pageup = layer.open({
               type: 2,
               title: '指派',
               shadeClose: true,
               shade: 0.8,
               area: ['80%', '80%'],
               content: '<?=\yii\helpers\Url::to(['/examine/examine/assign-personal'])?>&id='+str
           });
       }
    });

    //单个指派
    $('.Assign').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '指派',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/examine/examine/assign-personal'])?>&id='+id
        });
    })

    //取消指派
    $('.qxzp').click(function(){
        var id = $(this).attr('id');
        layer.confirm('你确定要取消指派吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['no-assign'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id},
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