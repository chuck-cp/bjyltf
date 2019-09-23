<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\schedules\models\SystemAdvert;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\schedules\models\search\SystemAdvertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '等待日广告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-advert-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <span style="margin-right: 30px; font-size: 20px;">等待日广告列表</span>
        <?= Html::a('新增等待日广告', ['create'], ['class' => 'btn btn-success create']) ?>&nbsp;&nbsp;&nbsp;
        <?= Html::a('新增广告', ['create_c'], ['class' => 'btn btn-success create']) ?>
        <?if($sysdateModel!=0):?>
            <?=Html::a('推送审核','javascript:void(0);',['class'=>'btn btn-success pushcheck'])?>
        <?else:?>
            <?=Html::a('推送审核','javascript:void(0);',['class'=>'btn btn-success','style'=>'background-color:#ddd;'])?>
        <?endif;?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '广告ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '广告类型',
                'value' => function($searchModel){
                    if($searchModel->advert_type==1){
                        return '等待日广告';
                    }else{
                        return '系统广告';
                    }
                }
            ],
            [
                'label' => '广告名称',
                'value' => function($searchModel){
                    return $searchModel->advert_name;
                }
            ],
            [
                'label' => '广告位',
                'value' => function($searchModel){
                    return SystemAdvert::getAdvertPositionKey($searchModel->advert_position_key);
                }
            ],
            [
                'label' => '店铺名称',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '链接地址',
                'value' => function($searchModel){
                    return $searchModel->link_url;
                }
            ],
            [
                'label' => '广告时长',
                'value' => function($searchModel){
                    return $searchModel->advert_time;
                }
            ],
            [
                'label' => '投放日期',
                'value' => function($searchModel){
                    return $searchModel->start_at.'至'.$searchModel->end_at;
                }
            ],
            [
                'label' => '投放频次',
                'value' => function($searchModel){
                    return $searchModel->throw_rate;
                }
            ],
            [
                'label' => '创建时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '广告状态',
                'value' => function($searchModel){
                    if($searchModel->throw_status==0){
                        return '未推送';
                    }else if($searchModel->throw_status==1){
                        return '已推送';
                    }else if($searchModel->throw_status==2){
                        return '投放完成';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {delete} {push} {views}',
                'buttons' => [
                    'edit' => function($url,$searchModel){
                        /*if($searchModel->throw_status==0 || $searchModel->throw_status==2){*/
                            if($searchModel->advert_type==2){
                                return html::a('编辑',['update_c','id'=>$searchModel->id]);
                            }elseif($searchModel->advert_type==1){
                                return html::a('编辑',['update','id'=>$searchModel->id]);
                            }
                       /* }*/

                    },
                    'delete' => function($url,$searchModel){
                        /*if($searchModel->throw_status==0 || $searchModel->throw_status==2){*/
                            return html::a('删除','javascript:void(0);',['class'=>'delete','id'=>$searchModel->id]);
                       /* }*/

                    },
                    'push' => function($url,$searchModel){
                        if($searchModel->throw_status==0){
                            return html::a('推送','javascript:void(0);',['class'=>'push','id'=>$searchModel->id]);
                        }

                    },
                    'views' => function($url,$searchModel){
                        if($searchModel->throw_status==1 || $searchModel->throw_status==2){
                            return html::a('查看详情',['view','id'=>$searchModel->id]);
                        }

                    },

                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.delete').click(function(){
        var id = $(this).attr('id');
        layer.confirm('删除后将不再显示，请确定是否删除该广告？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['delete'])?>&id='+id,
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
    //推送
    $('.push').click(function(){
        var id = $(this).attr('id');
        layer.confirm('推送后广告内容将无法修改和删除，并按设定时间进行播放请确定是否推送该广告？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['push'])?>&id='+id,
                type : 'POST',
                dataType : 'json',
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
    //推送审核
    $('.pushcheck').on('click',function(){
        $(this).attr("disabled", "disabled");
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['push-check'])?>',
            type : 'GET',
            dataType : 'json',
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
    })
</script>