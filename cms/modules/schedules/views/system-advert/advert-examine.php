<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->title = '等待日广告管理审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-advert-index">

    <div class="screen-run-time-shop-subsidy-search">
        <?php $form = ActiveForm::begin([
            'action' => ['advert-examine-list'],
            'method' => 'get',
        ]); ?>
        <div class="row">
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'date_start')->textInput(['placeholder'=>'开始时间','class'=>'form-control fm datepicker'])->label('推送时间');?>
            </div>
            <div class="col-xs-2 form-group">
                <?=$form->field($searchModel,'date_end')->textInput(['placeholder'=>'结束时间','class'=>'form-control fm datepicker'])->label(false);?>
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
          //  ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '广告投放日期',
                'value' => function($searchModel){
                    return $searchModel->date;
                }
            ],
            [
                'label' => '创建时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '审核状态',
                'value' => function($searchModel){
                    if($searchModel->examine_status==0 && $searchModel->examine_number==0){
                        return '待一审';
                    }elseif($searchModel->examine_status==0 && $searchModel->examine_number==1){
                        return '待二审';
                    }elseif($searchModel->examine_status==1){
                        return '已通过';
                    }elseif($searchModel->examine_status==2){
                        return '已驳回';
                    }
                    return $searchModel->examine_status;
                }
            ],
            [
                'label' => '审核次数',
                'value' => function($searchModel){
                    return $searchModel->examine_number;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {delete}',
                'buttons' => [
                    'edit' => function($url,$searchModel){
                        if($searchModel->examine_status==0){
                            return Html::a('通过','javascript:void(0);',['class'=>'adopt','id'=>$searchModel->id,'type'=>'1']);
                        }
                    },
                    'delete' => function($url,$searchModel){
                        if($searchModel->examine_status==0){
                            return Html::a('驳回','javascript:void(0);',['class'=>'rebut','id'=>$searchModel->id,'type'=>'2']);
                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.adopt').click(function(){
        var id = $(this).attr('id');
        var type = $(this).attr('type');
        layer.confirm('你确定要执行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['advert-examine'])?>&id='+id,
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'type':type},
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
    //推送
    $('.rebut').click(function(){
        var id = $(this).attr('id');
        var type = $(this).attr('type');
        pg = layer.open({
            type: 1,
            title:'等待日广告审核',
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            shadeClose: true,
            content:'<div style="text-align: center; margin-top:30px;"><label>请填写驳回原因</label></div><div style="text-align: center;margin-top:10px;"><label><textarea style="width:200px;height: 80px;border-radius:5px; border:solid 1px #ccc;padding-top: 5px;"  type="text" name="rebut_advert" ></textarea></label></div><div style="text-align: center; margin-top:30px;"><label><button type="button" style="margin-right:30px;" class="btn btn-primary qx" data-type="rebut">取消</button><button type="button" class="btn btn-primary qd" data-type="rebut">确定</button></label><div>',
        });
        $('.qx').click(function(){
            layer.closeAll();
        })

        $('.qd').click(function(){
            var layerMsg = layer.load('请稍后...',{
                icon: 0,
                shade: [0.1,'black']
            });
            var rebut_advert  = $('textarea[name="rebut_advert"]').val();
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['advert-examine'])?>&id='+id,
                type : 'POST',
                dataType : 'json',
                data : {'rebut_advert':rebut_advert,'id':id,'type':type},
                success:function (data){
                    if(data.code==1){
                        layer.msg(data.msg, {
                            icon: 1,
                            time: 2000,
                            end:function(){
                                window.parent.location.reload();
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            layer.closeAll();
                        },2000);

                    }
                },
                error:function () {
                    layer.msg('操作失败！');
                    layer.closeAll();
                }
            });
        })
    })
</script>