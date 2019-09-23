<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = '屏幕管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shop_search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>
    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.area').change(function () {
                var type = $(this).attr('key');
                var selObj = $('[key='+type+']').parents('.col-xs-2');
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
            //点击查看详情
            $('.chakan').click(function () {
                var shop_id = $(this).attr('shop_id');
                layer.open({
                    type: 2,
                    title: '查看',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['60%', '60%'],
                    content: '<?=\yii\helpers\Url::to(['/screen/screen/screen'])?>&shop_id='+shop_id //iframe的url
                });
            })
            //点击指派详情
            $('.zhipai').click(function () {
                var shop_id = $(this).attr('shop_id');
                layer.open({
                    type: 2,
                    title: '指派',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['70%', '90%'],
                    content: '<?=\yii\helpers\Url::to(['/screen/screen/designate'])?>&shop_id='+shop_id //iframe的url
                });
            })

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
                /*$('.table-bordered tr').each(function(){
                    var selected=$(this).find('td').eq(0).find('input').prop('checked')
                    console.log(selected);
                    if(selected){
                        var wzp=$(this).find('td').eq(13).html();
                        if(wzp=='已指派'){
                            layer.msg('已存在已指派的店铺，请勿重复选择',{icon:2});
                            ininfo=false;
                            return false;
                        }
                    }
                })
                return false;*/
                if(ininfo){
                    var pageup = layer.open({
                        type: 2,
                        title: '指派',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['80%', '80%'],
                        content: '<?=\yii\helpers\Url::to(['/screen/screen/designate'])?>&shop_id='+str //iframe的url
                    });
                }
            });
        })
    </script>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'id')->textInput(['class'=>'form-control fm'])->label('商家信息');?>
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
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'acreage')->dropDownList(['0'=>'由高到低','1'=>'由低到高'],['class'=>'form-control fm','prompt'=>'全部'])->label('店铺面积');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=$form->field($searchModel,'member_name')->textInput(['class'=>'form-control fm'])->label('业务员');?>
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
            'name',
            'member_name',
            [
              'label'=>'管理员',
                'value'=>function($modela){
                    return $modela->admin['name'] == 0?'玉龙传媒':$modela->admin['name'];
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return  $searchModel->area_name;
                }
            ],
//            [
//                'label' => '所属省',
//                'value' => function($searchModel){
//                    return  SystemAddress::getAreaByIdLen($searchModel->area,5);
//                }
//            ],
//            [
//                'label' => '所属市',
//                'value' => function($searchModel){
//                    return SystemAddress::getAreaByIdLen($searchModel->area,7);
//                }
//            ],
//            [
//                'label' => '所属区',
//                'value' => function($searchModel){
//                    return SystemAddress::getAreaByIdLen($searchModel->area,9);
//                }
//            ],
//            [
//                'label' => '所属街道',
//                'value' => function($searchModel){
//                    return SystemAddress::getAreaByIdLen($searchModel->area,11);
//                }
//            ],
            'acreage',
            'mirror_account',
            'screen_number',
            'error_screen_number',
            'create_at',
            [
                'label' => '指派',
                'value' => function($searchModel){
                    if($searchModel->admin_member_id==0){
                        return '未指派';
                    }else{
                        return '已指派';
                    }
                }
            ],
//            [
//                'label' => '申请状态',
//                'value' => function($searchModel){
//                     return \cms\modules\shop\models\Shop::getStatusByNum($searchModel->status);
//                }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {designate}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return html::tag('span','查看',['class'=>'detail chakan','shop_id'=>$searchModel->id]);
                    },
                    'designate' => function($url,$searchModel){
                        if($searchModel->admin_member_id ==0){
                            return html::tag('span','指派',['class'=>'detail zhipai','shop_id'=>$searchModel->id]);
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
    .detail:hover{cursor:pointer;}
</style>