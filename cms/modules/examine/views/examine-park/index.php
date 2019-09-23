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
    <?php echo $this->render('layout/shop_examine',['model'=>$searchModel]);?>
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
                        return Html::a('查看详情',['examine-park/view','id'=>$searchModel->id]);
                    },
                    'assign' => function($url,$searchModel){
                        if($searchModel->poster_examine_status == 2){
                            if($searchModel->poster_install_member_id==0){
                                return html::a('指派电工','javascript:void(0);',['class'=>'Assign','id'=>$searchModel->id,'screen_type'=>'poster']);
                            }else{
                                return html::a('取消指派','javascript:void(0);',['class'=>'qxzp','id'=>$searchModel->id,'screen_type'=>'poster']);
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
            content: '<?=\yii\helpers\Url::to(['/examine/examine-park/audit-assign-list'])?>&id='+id+'&screen_type='+screen_type
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