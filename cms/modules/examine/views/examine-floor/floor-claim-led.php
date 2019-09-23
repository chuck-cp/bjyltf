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
    <?php echo $this->render('layout/shop_option',['model'=>$searchModel]);?>
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
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        if(Yii::$app->user->identity->member_group>0){
                            return html::a('认领','javascript:void(0);',['class'=>'floor-claim-led','id'=>$searchModel->id,'type'=>'led']);
                        }else{
                            return '无法认领';
                        }
                    }
                ],
            ],
        ],
    ]); ?>
</div>

<script type="text/javascript">
    $('.floor-claim-led').click(function(){
        var id = $(this).attr('id');
        var type = $(this).attr('type');
        layer.confirm('确定认领？', {
            title:'商家认领',
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['floor-confirm-claim'])?>',
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
</script>