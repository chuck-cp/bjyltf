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
    <?php echo $this->render('layout/install_examine',['model'=>$searchModel]);?>
    <?php echo $this->render('layout/tab',['model'=>$searchModel]);?>
    <?php echo $this->render('postersearch', ['searchModel' => $searchModel]); ?>

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
                    return  $searchModel->poster_examine_at;
                }
            ],
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return  BuildingShopFloor::getStatusfloor($searchModel->poster_examine_status) ;
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
                    return  $searchModel->poster_install_finish_at;
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
                'template' => '{view}  {lable}  {store_adver} {close}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['examine-floor/poster-view','id'=>$searchModel->id,'type'=>2]);
                    },
                    'lable' => function($url,$searchModel){
                        return Html::a('标签','javascript:void(0);',['class'=>'lable','shopid'=>$searchModel->id]);
                    },
                    'store_adver' => function($url,$searchModel){
                        return Html::a('开启店铺广告','javascript:void(0);',[]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
