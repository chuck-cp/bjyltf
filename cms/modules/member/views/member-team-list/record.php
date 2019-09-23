<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MemberSearchTeamList */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo $this->render('layout/tab', ['member_id' => $member_id,'teamObj'=>$teamObj,'mobile'=>$mobile])?>
<?php  echo $this->render('_shop_search', ['model' => $searchModel,'member_id'=>$member_id,'mobile'=>$mobile]); ?>

<div class="member-team-list-index">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'label' => '店铺地址',
                'value' => function($searchModel){
                    return $searchModel->area_name.$searchModel->address;
                }
            ],
            [
                'label' => '安装屏幕数量',
                'value' => function($searchModel){
                    return $searchModel->screen_number;
                }
            ],


            [
                'label' => '被指派人姓名',
                'value' => function($searchModel){
                    return $searchModel->install_member_name;
                }
            ],

            [
                'label'=>'被指派人联系电话',
                'value' => function($searchModel){
                    return $searchModel->installMobile;
                }
            ],

            [
                'label' => '安装状态',
                'value' => function($searchModel){
                    return $searchModel->status == 5 ? '已安装' : '未安装';
                }
            ],

            //'install_shop_number',
            //'install_screen_number',
            //'wait_shop_number',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看',['/shop/shop/view','id'=>$searchModel->id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>