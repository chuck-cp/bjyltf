<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use cms\modules\examine\models\ShopScreenReplace;

$this->title = '换屏指派';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-replace-index">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-2 form-group" style="padding-right: 0px;">
            <?=$form->field($searchModel,'shop_name')->textInput(['class'=>'form-control fm'])->label('商家名称');?>
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
            <?=$form->field($searchModel,'zhipai_status')->dropDownList(['1'=>'已指派','2'=>'未指派'],['class'=>'form-control fm','prompt'=>'全部'])->label('是否指派');?>
        </div>
        <div class="col-xs-2 form-group">
            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '维护类型',
                'value' =>function($model){
                    return ShopScreenReplace::getMaintainType($model->maintain_type);
                }
            ],
            'shop_name',
            'shop_area_name',
            'replace_screen_number',
            'install_member_name',
            'assign_time',
            'create_at',
            [
                'label' => '状态',
                'value' =>function($model){
                    return ShopScreenReplace::getStatus($model->status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '标签',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$model) use ($LableArr){
                        $labels = [];
                        foreach ($LableArr as $v){
                            foreach (explode(',',$model->lable_id) as $vv){
                                if($v['id']==$vv){
                                    $labels[] = Html::a($v['title'],'javascript:void(0);',['title'=>$v['desc'],'shopid'=>$model->id]);
                                }
                            }
                        }
                        return implode(',',$labels);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {zhipai} {repeal}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('查看详情',['/shop/shop/view','id'=>$model->shop_id],['target'=>'_blank']);
                    },
                    'zhipai' => function($url,$model){
                        if($model->install_member_id>0){
                            return html::a('取消指派','javascript:void(0);',['class'=>'qxzp','id'=>$model->id]);
                        }else{
                            return html::a('指派电工','javascript:void(0);',['class'=>'Assign','id'=>$model->id]);
                        }
                    },
                    'repeal' => function($url,$model){
                        if($model->install_member_id==0){
                            return html::a('撤销维护','javascript:void(0);',['class'=>'repeal','id'=>$model->id]);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //地区切换
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
    })
    //指派
    $('.Assign').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '换屏指派',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/examine/shop-screen-replace/assign-member'])?>&id='+id
        });
    })
    //取消指派
    $('.qxzp').click(function(){
        var id = $(this).attr('id');
        layer.confirm('你确定要取消指派吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['no-reassign'])?>',
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
    //撤销维护
    $('.repeal').click(function(){
        var id = $(this).attr('id');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['repeal'])?>',
            type : 'POST',
            dataType : 'json',
            data : {'id':id},
            success:function (data) {
                if(data==1){
                    layer.msg('撤销成功',{icon:1});
                    setTimeout(function(){
                        window.parent.location.reload();
                    },2000);
                }else{
                    layer.msg('撤销失败',{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！');
            }
        });
    })
</script>