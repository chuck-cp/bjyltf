<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\shop\models\BuildingShopFloor;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\shop\models\search\BuildingShopFloorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '楼宇信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-shop-floor-index">
    <?php echo $this->render('layout/shop_examine',['model'=>$searchModel]);?>
    <?php echo $this->render('layout/tab',['model'=>$searchModel]);?>
    <?php echo $this->render('ledsearch', ['searchModel' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => '楼宇名称',
                'value' => function($searchModel){
                    return  $searchModel->shop_name;
                }
            ],
            [
                'label' => '楼宇类型',
                'value' => function($searchModel){
                    if($searchModel->floor_type == 1){
                        return '写字楼';
                    }else if($searchModel->floor_type == 2){
                        return '商住两用';
                    }else{
                        return '未设置';
                    }
                }
            ],
            [
                'label' => '楼宇等级',
                'value' => function($searchModel){
                    return  $searchModel->shop_level;
                }
            ],
            [
                'label' => '安装类型',
                'value' => function($searchModel){
                    return  'LED';
                }
            ],
            [
                'label' => '地址',
                'value' => function($searchModel){
                    return  $searchModel->address;
                }
            ],
            [
                'label' => '公司名称',
                'value' => function($searchModel){
                    return  $searchModel->buildingCompany['company_name']?$searchModel->buildingCompany['company_name']:'';
                }
            ],
            [
                'label' => '统一社会代码',
                'value' => function($searchModel){
                    return  $searchModel->buildingCompany['company_name']?$searchModel->buildingCompany['company_name']:'';
                }
            ],
            [
                'label' => '申请人',
                'value' => function($searchModel){
                    return  $searchModel->buildingCompany['apply_name']?$searchModel->buildingCompany['apply_name']:'';
                }
            ],
            [
                'label' => '联系人姓名',
                'value' => function($searchModel){
                    return  $searchModel->contact_name;
                }
            ],[
                'label' => '联系人电话',
                'value' => function($searchModel){
                    return  $searchModel->contact_mobile;
                }
            ],
            [
                'label' => '审核通过时间',
                'value' => function($searchModel){
                    return  $searchModel->led_examine_at;
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return  BuildingShopFloor::getStatusfloor($searchModel->led_examine_status);
                }
            ],
            [
                'label' => '合同状态',
                'value' => function($searchModel){
                    if($searchModel->buildingShopContract['status']==1){
                        return '正常';
                    }else if($searchModel->buildingShopContract['status']==2){
                        return '作废';
                    }else{
                        return '';
                    }
                    /*if($searchModel->shopContract['examine_status']==1){
                        return '通过';
                    }elseif ($searchModel->shopContract['examine_status']==2){
                        return '驳回';
                    }else{
                        return '待审核';
                    }*/
                }
            ],
            [
                'label' => '安装完成时间',
                'value' => function($searchModel){
                    return  $searchModel->led_install_finish_at;
                }
            ],
            [
                'label' => '合同审核通过时间',
                'value' => function($searchModel){
                    return  $searchModel->buildingShopContract['examine_at']?$searchModel->buildingShopContract['examine_at']:'';
                }
            ],
            [
                'label' => '标签',
                'value' => function($searchModel){
                    return  $searchModel->shop_name;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {assign}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['examine-floor/view','id'=>$searchModel->id,'type'=>1]);
                    },
                    'assign' => function($url,$searchModel){
                        if($searchModel->led_examine_status == 2){
                            if($searchModel->led_install_member_id==0){
                                return html::a('指派电工','javascript:void(0);',['class'=>'Assign','id'=>$searchModel->id,'screen_type'=>'led']);
                            }else{
                                return html::a('取消指派','javascript:void(0);',['class'=>'qxzp','id'=>$searchModel->id,'screen_type'=>'led']);
                            }
                        }
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script>
    //单个指派
    $('.Assign').click(function () {
        var id = $(this).attr('id');
        var screen_type = $(this).attr('screen_type');
        var pageup = layer.open({
            type: 2,
            title: '指派',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '80%'],
            content: '<?=\yii\helpers\Url::to(['/examine/examine-floor/audit-assign-list'])?>&id='+id+'&screen_type='+screen_type
        });
    })
    //取消指派
    $('.qxzp').click(function(){
        var id = $(this).attr('id');
        var screen_type = $(this).attr('screen_type');
        layer.confirm('你确定要取消指派吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['no-audit-assign'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'screen_type':screen_type},
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