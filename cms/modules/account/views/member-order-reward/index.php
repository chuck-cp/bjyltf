<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\MemberOrderRewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告销售奖励支出';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-order-reward-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <table style="border: 1px solid #dddddd; width: 19%; margin-bottom: 20px;text-align: center;" >
        <tr>
            <th style="text-align: center;" >
                <p style="margin-top: 10px;font-size: 16px;">
                    支出总额：<?echo $TotalPrice?>
                </p>
            </th>
        </tr>
    </table>

    <?= \cms\core\CmsGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'ID',
                'value' => function($searchModel){
                    return $searchModel->id;
                }
            ],
            [
                'label' => '订单编号',
                'value' => function($searchModel){
                    return $searchModel->order_id;
                }
            ],
            [
                'label' => '生成时间',
                'value' => function($searchModel){
                    return $searchModel->create_at;
                }
            ],
            [
                'label' => '完成时间',
                'value' => function($searchModel){
                    return $searchModel->order_finish_at;
                }
            ],
            [
                'label' => '商家编号',
                'value' => function($searchModel){
                    return $searchModel->shop_id;
                }
            ],
            [
                'label' => '商家名称',
                'value' => function($searchModel){
                    return $searchModel->shop_name;
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    return $searchModel->area_name;
                }
            ],
            [
                'label' => '法人姓名',
                'value' => function($searchModel){
                    return $searchModel->shopApply['apply_name'];
                }
            ],
            [
                'label' => '法人手机号',
                'value' => function($searchModel){
                    return $searchModel->shopApply['apply_mobile'];
                }
            ],
            [
                'label' => '购买产品',
                'value' => function($searchModel){
                    return $searchModel->goods_name;
                }
            ],
            [
                'label' => '交易费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->order_price);
                }
            ],
            [
                'label' => '奖励费用',
                'value' => function($searchModel){
                    return ToolsClass::priceConvert($searchModel->reward_price);
                }
            ],
            [
                'label' => '屏幕编号',
                'value' => function($searchModel){
                    return $searchModel->screen_number;
                }
            ],
        ],
    ]); ?>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $('.view').click(function(){
        var id=$(this).attr('id');
        var pageup = layer.open({
            type: 2,
            title: '详情',
            shadeClose: true,
            shade: 0.8,
            area: ['30%', '30%'],
            content: '<?=\yii\helpers\Url::to(['/account/member-order-reward/view'])?>&id='+id
        });
    })
</script>
