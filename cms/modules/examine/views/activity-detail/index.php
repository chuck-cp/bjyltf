<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\ActivityDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '店铺推荐信息';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
?>
<div class="activity-detail-index">

    <?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>


    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           /* ['class' => 'yii\grid\SerialColumn'],*/

            'id',
            [
                'label' => '推荐人',
                'value' => function($searchModel){
                    return $searchModel->activity['member_name'];
                }
            ],
            [
                'label' => '推荐账号',
                'value' => function($searchModel){
                    return $searchModel->activity['member_mobile'];
                }
            ],
            [
                'label' => '推荐时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '店铺名称',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '店铺地址',
                'value' => function($searchModel){
                    return $searchModel->area_name.$searchModel->address;
                }
            ],
            [
                'label' => '店铺联系人',
                'value' => function($searchModel){
                    return $searchModel->apply_name;
                }
            ],
            [
                'label' => '店铺联系方式',
                'value' => function($searchModel){
                    return $searchModel->apply_mobile;
                }
            ],
            [
                'label' => '对接业务合作人',
                'value' => function($searchModel){
                    return $searchModel->custom_member_name;
                }
            ],
            [
                'label' => '对接业务员账号',
                'value' => function($searchModel){
                    return $searchModel->memberMobile['mobile']?$searchModel->memberMobile['mobile']:'';
                }
            ],
            [
                'label' => '业务合作人所属办事处',
                'value' => function($searchModel){
                    return '';
                }
            ],
            [
                'label' => '指派状态',
                'value' => function($searchModel){
                    if($searchModel->order_source==0){
                        return '未指派';
                    }else if($searchModel->order_source==1){
                        return '上下级指派';
                    }else if($searchModel->order_source==2){
                        return '人工指派';
                    }
                }
            ],
            [
                'label' => '状态',
                'value' => function($searchModel){
                    if($searchModel->status==0){
                        return '未签约';
                    }elseif ($searchModel->status==1){
                        return '已签约';
                    }elseif ($searchModel->status==3){
                        return '已签约';
                    }else{
                        return '签约失败';
                    }
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{butt} {view}',
                'buttons' => [
                    'butt' => function($url,$searchModel){
                        if($searchModel->is_apply==0){
                            if($searchModel->status!==1){
                                if($searchModel->custom_member_id==0){
                                    return Html::a('指派对接人','javascript::void(0)',['class'=>'Assign','id'=>$searchModel->id]);
                                }else{
                                    return Html::a('取消对接人','javascript::void(0)',['class'=>'qx','id'=>$searchModel->id]);
                                }
                            }
                        }

                    },
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['/examine/activity-detail/view','id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //单个指派
    $('.Assign').click(function () {
        var id = $(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '指派',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['/examine/activity-detail/assign-butt'])?>&id='+id
        });
    })

    $('.qx').click(function(){
        var id=$(this).attr('id');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['cancel'])?>',
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
        })
    })
</script>
