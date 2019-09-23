<?php

use yii\helpers\Html;
use yii\grid\GridView;
use cms\models\SystemAddress;
use cms\models\TbInfoRegion;
use cms\models\LogExamine;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '人员审核';
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'member_id',
            'name',
            [
                'label' => '手机',
                'value' => function($model){
                    return $model->member['mobile'] == ""?'---':$model->member['mobile'];
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($model){
                     return $model->member['area_name'];
//                     return SystemAddress::getAreaNameById($model->member['area']);
                }
            ],
//            [
//                'label' => '所属省',
//                'value' => function($model){
//                     return SystemAddress::getAreaByIdLen($model->area,5);
//                }
//
//            ],
//            [
//                'label' => '所属市',
//                'value' => function($model){
//                    return SystemAddress::getAreaByIdLen($model->area,7);
//                }
//            ],
//            [
//                'label' => '所属区/县',
//                'value' => function($model){
//                    return SystemAddress::getAreaByIdLen($model->area,9);
//                }
//            ],
//            [
//                'label' => '所属街道',
//                'value' => function($model){
//                    return SystemAddress::getAreaByIdLen($model->area,12);
//                }
//            ],
            [
                'label' => '商家数量',
                'value' => function($model){
                    return $model->memCount['shop_number'] == ''?0:$model->memCount['shop_number'];
                }
            ],
            [
                'label' => 'LED数量',
                'value' => function($model){
                    return $model->memCount['screen_number'] == ''?0:$model->memCount['screen_number'];
                }
            ],
            [
                'label' => '状态',
                'value' => function($searchModel){
                    return \cms\modules\member\models\MemberInfo::getMemberStatus($searchModel->examine_status);
                }
            ],
            [
                'label' => '审核人员',
                'value' => function($searchModel){
                    return LogExamine::getShopCheckMan($searchModel->member_id,2)['create_user_name'] == ''?'---':LogExamine::getShopCheckMan($searchModel->member_id,2)['create_user_name'];
                }
            ],
            [
                'label' => '身份证审核提交时间',
                'value' => function($searchModel){
                    return $searchModel->apply_at;
                }
            ],
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                        'view' => function($url,$searchModel){
                            return Html::a('查看详情',['chef/view','id'=>$searchModel->member_id]);
                        }
                ],
            ],
        ],
    ]); ?>
</div>
