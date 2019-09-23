<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\models\SystemAddress;
use yii\helpers\Url;
use cms\models\TbInfoRegion;
use cms\modules\member\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '人员查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\CheckboxColumn'],*/
            'id',
            'name',
            'mobile',
            [
                'label' => '所属地区',
                'value' => function($model){
                    return SystemAddress::getAreaNameById($model->area);
                }

            ],
            [
                'label' => '业务区域',
                'value' => function($model){
                    return SystemAddress::getAreaByIdLen($model->admin_area,9);
                }
            ],
            [
                'label' => '收益总额',
                'value' => function($model){
                    return number_format($model->memberAccount['count_price']/100,2);
                }
            ],
            [
                'label' => '联系商家数量',
                'value' => function($model){
                    return $model->memCount['shop_number'] ? $model->memCount['shop_number'] : 0;
                }
            ],
            [
                'label' => '联系LED数量',
                'value' => function($model){
                    return $model->memCount['screen_number'] ? $model->memCount['screen_number'] : 0;
                }
            ],
            [
                'label' => '安装商家数量',
                'value' => function($model){
                    return $model->memCount['install_shop_number'] ? $model->memCount['install_shop_number'] : 0;
                }
            ],
            [
                'label' => '安装LED数量',
                'value' => function($model){
                    return $model->memCount['install_screen_number'] ? $model->memCount['install_screen_number'] : 0;
                }
            ],
            [
                'label' => '是否为内部人员',
                'value' => function($model){
                    return $model->inside==1 ? '是':'否';
                }
            ],
            [
                'label' => '是否为电工',
                'value' => function($model){
                    return $model->memIdcardInfo['electrician_examine_status']==1 ? '是' : '否';
                }
            ],
            [
                'label' => '是否为内部电工',
                'value' => function($model){
                    return $model->memIdcardInfo['company_electrician']==1 ? '是' : '否';
                }
            ],
            [
                'label' => '是否为合作推广人',
                'value' => function($model){
                    if($model->inside==1){
                        return '否';
                    }else{
                        if($model->parent_id>0){
                            return '是';
                        }else{
                            return '否';
                        }
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {inside} {sign} {wireman} {part_time_business}',
                'buttons' => [
                    'view' => function($url,$model){
                        return Html::a('查看详情',['member/view','id'=>$model->id]);
                    },
                    'inside' => function($url,$model){
                        if($model->inside == 0){
                            return Html::a('设置内部人员','javascript:void(0);',['id'=>$model->id,'class'=>'noinside']);
                        }else{
                            return Html::a('取消内部人员','javascript:void(0);',['id'=>$model->id,'class'=>'inside']);
                        }
                    },
                    'sign'=>function($url,$model){
                        if($model->inside==1){
                            if($model->sign_team_admin==0){
                                return Html::a('设置签到管理人员','javascript:void(0);',['id'=>$model->id,'class'=>'sign']);
                            }else{
                                return Html::a('取消签到管理人员','javascript:void(0);',['id'=>$model->id,'class'=>'nosign']);
                            }
                        }
                    },
                    'wireman'=>function($url,$model){
                        if($model->quit_status==1){
                            return Html::a('取消离职人员','javascript:void(0);',['id'=>$model->id,'class'=>'noleavewireman']);
                        }else{
                            return Html::a('设置离职人员','javascript:void(0);',['id'=>$model->id,'class'=>'leavewireman']);
                        }
                    },
                    'part_time_business'=>function($url,$model){
                        if($model->part_time_business==1){
                            return Html::a('取消兼职人员','javascript:void(0);',['id'=>$model->id,'class'=>'nobusinessman']);
                        }else{
                            return Html::a('设置兼职人员','javascript:void(0);',['id'=>$model->id,'class'=>'businessman']);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $('.noinside').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/inside'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'inside':1},
            success:function (sre) {
                if(sre==1){
                    layer.msg('设置成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('设置失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    })
    $('.inside').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/inside'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'inside':0},
            success:function (sre) {
                if(sre==1){
                    layer.msg('取消成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('取消失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    })
    $('.nosign').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/sign-setup'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'sign':0},
            success:function (sre) {
                if(sre==1){
                    layer.msg('取消成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('取消失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    })
    $('.sign').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/sign-setup'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'sign':1},
            success:function (sre) {
                if(sre==1){
                    layer.msg('设置成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('设置失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    })
    //离职人员设置
    $('.leavewireman').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/leave-wireman'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'quit_status':1},
            success:function (sre) {
                if(sre==1){
                    layer.msg('设置成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('设置失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    });
    //取消离职人员
    $('.noleavewireman').on('click',function(){
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/leave-wireman'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'quit_status':0},
            success:function (sre) {
                if(sre==1){
                    layer.msg('取消成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('取消失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    });
    //设置兼职业务人员
    $('.businessman').on('click',function () {
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/part-time-business'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'status':1},
            success:function (sre) {
                if(sre==1){
                    layer.msg('设置成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('设置失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    });
    //取消设置兼职业务人员
    $('.nobusinessman').on('click',function () {
        var id=$(this).attr('id');
        $.ajax({
            url: '<?=Url::to(['/member/member/part-time-business'])?>',
            type: 'GET',
            dataType: 'json',
            data:{'id':id,'status':0},
            success:function (sre) {
                if(sre==1){
                    layer.msg('取消成功！');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.msg('取消失败！');
                }
            },error:function () {
                layer.msg('修改失败！');
            }
        })
    });
</script>