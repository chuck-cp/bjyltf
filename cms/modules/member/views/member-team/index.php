<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\member\models\search\MembeTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '安装团队列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-team-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
<!--        --><?//= Html::a('Create Member Team', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'member_id',
            'team_name',
            'team_member_name',
            //联系电话
            [
                'label' => '联系电话',
                'value' => function($searchModel){
                    return $searchModel->mobile;
                }
            ],
            //'live_area_id',
            //'live_area_name:raw',
            [
                'label' => '现住地址',
                'value' => function($searchModel){
                    return html_entity_decode($searchModel->live_area_name.' '.$searchModel->live_address);
                },
            ],
            //'live_address',
            'company_name',
            [
                'label' => '公司地址',
                'value' => function($searchModel){
                    return html_entity_decode($searchModel->company_area_name.' '.$searchModel->company_address);
                }
            ],
            //'company_area_id',
            //'company_address',
            //'install_shop_number',
            //'not_install_shop_number',
            //'not_assign_shop_number',
            'create_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {dissolve}',
                'header' => '操作',
                'buttons' => [
                    'view' => function($url,$searchModel){
                        return Html::a('查看成员及指派记录',['member-team-list/index','team_id'=>$searchModel->id,'mobile'=>$searchModel->mobile]);
                    },
                    'dissolve' => function($url,$searchModel){
                        return $searchModel->installNum == 0 ? Html::tag('span','没有可解除订单') : Html::tag('span','强制解除团队订单',['team_member_id'=>$searchModel->id,'class'=>'dissolve cursor']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.dissolve').click(function () {
            var team_member_id = $(this).attr('team_member_id');
            var __this = $(this);
            layer.confirm('确定要解除该团队订单么？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['/member/member-team/dissolve'])?>',
                    type: 'POST',
                    dataType: 'json',
                    data:{'team_member_id':team_member_id},
                    success:function (phpdata) {
                        if(phpdata){
                            layer.msg('解除成功！');
                            window.location.reload();
                        }else{
                            layer.msg('解除失败！');
                        }
                    },error:function (phpdata) {
                        layer.msg('解除失败！');
                    }
                })
            }, function(){
                layer.msg('您已取消操作', {
                    time: 2000, //20s后自动关闭
                    //btn: ['明白了', '知道了']
                });
            });


        })
    })
</script>
