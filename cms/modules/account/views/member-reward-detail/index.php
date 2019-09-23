<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\libs\ToolsClass;
/* @var $this yii\web\View */
/* @var $searchModel cms\modules\account\models\search\MemberRewardDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告销售奖励支出';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-reward-detail-index">
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

           // ['class' => 'yii\grid\SerialColumn'],
            'id',
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
                    return $searchModel->finish_at;
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
                    return $searchModel->rewardMember['shop_name'];
                }
            ],
            [
                'label' => '总部名称',
                'value' => function($searchModel){
                    return $searchModel->shopHeadquarters['company_name']?$searchModel->shopHeadquarters['company_name']:'--';
                }
            ],
            [
                'label' => '所属地区',
                'value' => function($searchModel){
                    if($searchModel->search_head_id==0){
                        return $searchModel->shop['area_name']?$searchModel->shop['area_name']:'--';
                    }else{
                        return $searchModel->shopHeadquarters['company_area_name']?str_replace(' &gt; ','',$searchModel->shopHeadquarters['company_area_name']):'--';
                    }
                }
            ],
            [
                'label' => '法人ID',
                'value' => function($searchModel){
                    if($searchModel->search_head_id==0){
                        return $searchModel->shop['shop_member_id']?$searchModel->shop['shop_member_id']:'--';
                    }else{
                        return $searchModel->shopHeadquarters['corporation_member_id']?$searchModel->shopHeadquarters['corporation_member_id']:'--';
                    }
                }
            ],
            [
                'label' => '法人姓名',
                'value' => function($searchModel){
                    if($searchModel->search_head_id==0){
                        return $searchModel->shopApply['apply_name']?$searchModel->shopApply['apply_name']:'--';
                    }else{
                        return $searchModel->shopHeadquarters['name']?$searchModel->shopHeadquarters['name']:'--';
                    }
                }
            ],
            [
                'label' => '法人手机号',
                'value' => function($searchModel){
                    if($searchModel->search_head_id==0){
                        return $searchModel->shopApply['apply_mobile']?$searchModel->shopApply['apply_mobile']:'--';
                    }else{
                        return $searchModel->shopHeadquarters['mobile']?$searchModel->shopHeadquarters['mobile']:'--';
                    }
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
                    return $searchModel->rewardMember['software_number'];
                }
            ],
        ],
    ]); ?>
</div>
