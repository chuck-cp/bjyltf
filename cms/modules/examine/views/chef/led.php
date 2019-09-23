<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\modules\member\models\MemberAccount;
$this->title = 'LED信息';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = ['label' => '人员审核', 'url' => ['index']];
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
                'label' => '所属省',
                'value' => function($searchModel){
                    return  SystemAddress::getAreaByIdLen($searchModel->area,5);
                    //return SystemAddress::getAreaNameById($searchModel->area);
                }
            ],
            [
                'label' => '所属市',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,7);
                }
            ],
            [
                'label' => '所属区',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,9);
                }
            ],
            [
                'label' => '所属街道',
                'value' => function($searchModel){
                    return SystemAddress::getAreaByIdLen($searchModel->area,11);
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
                        return Html::a('查看详情',['/shop/shop/view','id'=>$searchModel->id]);
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