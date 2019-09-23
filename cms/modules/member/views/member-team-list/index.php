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
<?php  echo $this->render('_search', ['model' => $searchModel,'member_id' => $member_id,'mobile'=>$mobile]); ?>

<div class="member-team-list-index">
    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'member_name',
            [
                'label' => '现住地址',
                'value' => function($searchModel){
                    return $searchModel->memberAddress;
                }
            ],
            [
                'label' => '联系电话',
                'value' => function($searchModel){
                    return $searchModel->memberPhone;
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
                        return Html::tag('span','查看安装任务',['javascript:void(0);','member_id'=>$searchModel->member_id,'class'=>'this_view']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $('.this_view').click(function () {
        var member_id = $(this).attr('member_id');
        layer.open({
            type: 2,
            title: '查看',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: '<?=\yii\helpers\Url::to(['install-task'])?>&member_id='+member_id //iframe的url
        });
    })
</script>
