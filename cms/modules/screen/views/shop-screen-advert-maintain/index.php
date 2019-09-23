<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\member\models\ReportMongo;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\screen\models\search\ShopScreenAdvertMaintainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告维护指派';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-screen-advert-maintain-index">
    <?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'shop_id',
            'shop_name',
            'apply_name',
            'apply_mobile',
            [
                'label'=>'到达率',
                'value' =>function($searchModel){
                    return ReportMongo::getArrivalRate($searchModel->mongo_id,'order_arrival_report').'%';
                }
            ],
            'shop_area_name',
            'apply_name',
            'apply_mobile',
            [
                'label' => '维护状态',
                'value' => function($searchModel){
                    if($searchModel->status==0){
                        return '待指派';
                    }else if($searchModel->status==1){
                        return '已指派';
                    }else if($searchModel->status==2){
                        return '维护完成';
                    }
                }
            ],
            [
                'label'=>'下发时间',
                'value' =>function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label'=>'指派电工姓名',
                'value' =>function($searchModel){
                    return $searchModel->install_member_name;
                }
            ],
            [
                'label'=>'电工电话',
                'value' =>function($searchModel){
                    return $searchModel->install_member_mobile;
                }
            ],
            [
                'label'=>'指派时间',
                'value' =>function($searchModel){
                    return $searchModel->assign_at;
                }
            ],
            [
                'label'=>'完成时间',
                'value' =>function($searchModel){
                    return $searchModel->install_finish_at;
                }
            ],
            'problem_description',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        if($searchModel->status==0){
                            return Html::a('指派','javascript:void(0);',['class'=>'assign','id'=>$searchModel->id,]);
                        }else if($searchModel->status==1){
                            return Html::a('取消指派','javascript:void(0);',['class'=>'cancelassign','id'=>$searchModel->id,]);
                        }else{
                            return '';
                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    //单个指派
    $('.assign').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '指派',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/screen/shop-screen-advert-maintain/electrician'])?>&id='+id
        });
    })

    $('.cancelassign').click(function(){
        var id = $(this).attr('id');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['cancel-maintain-assign'])?>',
            type : 'get',
            dataType : 'json',
            data : {'id':id},
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        parent.location.reload();
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
</script>
