<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = 'LED信息';
$this->params['breadcrumbs'][] = ['label' => '人员查询', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop_search">
    <?php echo $this->render('layout/tab',['model'=>$model]);?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'name',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return $searchModel->area_name;
                }
            ],
            'acreage',
            'mirror_account',
            'screen_number',
            'create_at',
            [
                'label' => '申请状态',
                'value' => function($searchModel){
                    return \cms\modules\shop\models\Shop::getStatusByNum($searchModel->status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情','javascript:void(0);',['class'=>'chakan','id'=>$searchModel->id]);
                    }
                ],
            ],
        ]

    ]);?>

</div>
<style type="text/css">
    .col-xs-2{padding-right: 0px!important;}
    .fm{width: 105px;display: inline-block;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //点击查看详情
    $('.chakan').click(function () {
        var shop_id = $(this).attr('id');
        layer.open({
            type: 2,
            title: '查看',
            shadeClose: true,
            shade: 0.8,
            area: ['480px', '35%'],
            content: '<?=\yii\helpers\Url::to(['/screen/screen/screen'])?>&shop_id='+shop_id //iframe的url
        });
    })
</script>