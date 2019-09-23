<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\modules\examine\models\ShopHeadquarters;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\ShopHeadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '总部审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-headquarters-index">
    
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'mobile',
            //'member_id',
            //'identity_card_num',
            //'identity_card_front',
            //'identity_card_back',
            'company_name',
            //'company_area_id',
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return html_entity_decode($searchModel->company_area_name);
                }
            ],
            //'company_address',
            //'registration_mark',
            //'business_licence',
            //'agreement_name',
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
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看详情',['view','id'=>$searchModel->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
