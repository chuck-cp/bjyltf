<?php

use yii\helpers\Html;
use cms\modules\examine\models\ShopHeadquarters;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\ShopHeadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '总部信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-headquarters-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'mobile',
            'company_name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return html_entity_decode($searchModel->company_area_name);
                }
            ],
            [
                'label' => '状态',
                'value' => function($searchModel){
                    return ShopHeadquarters::getStatusByNum($searchModel->examine_status);
                }
            ],
            'create_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {store_adver}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['view','id'=>$searchModel->id]);
                    },
                    'store_adver' => function($url,$searchModel){
                        if($searchModel->agreed==0)
                            return Html::a('开启店铺广告','javascript:void(0);',['class'=>'agreed','id'=>$searchModel->id,'agreed'=>$searchModel->agreed]);
                        return Html::a('关闭店铺广告','javascript:void(0);',['class'=>'agreed','id'=>$searchModel->id,'agreed'=>$searchModel->agreed]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script>
    $('.agreed').click(function(){
        var id=$(this).attr('id');
        var agreed=$(this).attr('agreed');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['store-adver-total'])?>&id='+id,
            type : 'POST',
            dataType : 'json',
            data : {'agreed':agreed},
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
