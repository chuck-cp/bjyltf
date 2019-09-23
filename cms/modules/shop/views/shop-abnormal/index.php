<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\shop\models\search\ShopAbnormalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '店铺屏幕信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-abnormal-index">

    <?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
         //   ['class' => 'yii\grid\SerialColumn'],
            'id',
            'shop_id',
            'shop_name',
            [
                'label' => '状态',
                'value' => function($searchModel){
                    return $searchModel->status==0?'未处理':'已处理';
                }
            ],
            'create_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {status}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['view','shop_id'=>$searchModel->shop_id,'shop_name'=>$searchModel->shop_name]);
                    },
                    'status' => function($url,$searchModel){
                        if($searchModel->status==1)
                            return '已处理';
                        return html::a('未处理','javascript:void(0);',['class'=>'status','id'=>$searchModel->id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $('.status').click(function(){
        var id = $(this).attr('id');
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['status'])?>',
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
    })

</script>
