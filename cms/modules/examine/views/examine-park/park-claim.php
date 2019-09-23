<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\shop\models\BuildingShopPark;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\shop\models\search\BuildingShopParkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公园信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-shop-park-index">
    <?php echo $this->render('layout/shop_option',['model'=>$searchModel]);?>
    <?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => '公园名称',
                'value' => function($searchModel){
                    return  $searchModel->shop_name;
                }
            ],
            [
                'label' => '公园等级',
                'value' => function($searchModel){
                    return  $searchModel->shop_level;
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
                'label' => '统一社会信用码',
                'value' => function($searchModel){
                    return  $searchModel->buildingCompany['registration_mark']?$searchModel->buildingCompany['registration_mark']:'';
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
            ],
            [
                'label' => '联系人电话',
                'value' => function($searchModel){
                    return  $searchModel->contact_mobile;
                }
            ],
            [
                'label' => '申请时间',
                'value' => function($searchModel){
                    return  $searchModel->poster_create_at;
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return  BuildingShopPark::getStatusPark($searchModel->poster_examine_status) ;
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
                            return html::a('认领','javascript:void(0);',['class'=>'park-claim','id'=>$searchModel->id]);
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
    $('.park-claim').click(function(){
        var id = $(this).attr('id');
        layer.confirm('确定认领？', {
            title:'商家认领',
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['pork-confirm-claim'])?>',
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