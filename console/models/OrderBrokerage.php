<?php

namespace console\models;
use Yii;
use yii\base\Exception;
use console\models\SystemAccount;
class OrderBrokerage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_brokerage}}';
    }

    /*
     * 关联order表
     */
    public function getOrder(){
        return $this->hasOne(Order::className(),['id'=>'order_id'])->select('count_payment_price');
    }


    /*
     * 计算订单佣金
     * @param string salesman_mobile 业务管理人手机号
     * @param string company_area_id 订单购买人所在地区
     * */
    public static function computeBrokerage($order){
        try{
            $resultMemberData = [];
            $memberModel = Member::find()->where(['id'=>$order['salesman_id']])->select('parent_id,admin_area')->asArray()->one();
            $orderBrokerModel = new OrderBrokerage();
            $orderBrokerModel->member_parent_id = $memberModel['parent_id'];
            $orderBrokerModel->member_id = $order['salesman_id'];
            $orderBrokerModel->member_name = $order['salesman_name'];
            $orderBrokerModel->member_mobile = $order['salesman_mobile'];
            $orderBrokerModel->order_id = $order['id'];
            $brokerageConfig = SystemConfig::getAllConfigById(['proportions','proportions_part_time_business','proportions_first','cooperation']);
            if ($order['part_time_order']) {
                $memberBrokerage = ceil($order['final_price'] * $brokerageConfig['proportions_part_time_business'] / 100);
            } else {
                $memberBrokerage = ceil($order['final_price'] * $brokerageConfig['proportions'] / 100);
            }
            if($memberModel['parent_id']){
                $orderBrokerModel->member_parent_price = ceil($memberBrokerage * $brokerageConfig['proportions_first'] / 100);
                $resultMemberData[] = ['member_id'=>$memberModel['parent_id'],'price'=>$orderBrokerModel->member_parent_price,'type' => 'member_parent_price'];
            }
            //计算业务配合费
            if(substr($order['company_area_id'],0,strlen($memberModel['admin_area'])) != $memberModel['admin_area']){
                $memberAdmin = Member::find()->where(['admin_area'=>substr($order['company_area_id'],0,9)])->select('id')->asArray()->all();
                if(!empty($memberAdmin)){
                    $cooperate_member_id = array_column($memberAdmin,'id');
                    $cooperate_member_number = count($cooperate_member_id);
                    $cooperate_money = ceil($memberBrokerage * $brokerageConfig['cooperation'] / 100);
                    $orderBrokerModel->cooperate_member_id = implode(",",$cooperate_member_id);
                    $orderBrokerModel->cooperate_money = $cooperate_money;
                    foreach($cooperate_member_id as $member_id){
                        $resultMemberData[] = ['member_id'=>$member_id,'price'=>intval($cooperate_money / $cooperate_member_number),'type' => 'cooperate'];
                    }
                }
            }
            $orderBrokerModel->member_price = ($memberBrokerage - (int)$orderBrokerModel->cooperate_money);
            $resultMemberData[] = ['member_id'=>$orderBrokerModel->member_id,'price'=>$orderBrokerModel->member_price,'type' => 'myself'];
            $orderBrokerModel->total = $orderBrokerModel->member_price + $orderBrokerModel->member_parent_price + $orderBrokerModel->cooperate_money;
            $orderBrokerModel->real_income = $order['final_price'] - $orderBrokerModel->total;
            $orderBrokerModel->save();
            //已完成订单总收入计算
            SystemAccount::updateAllCounters(['done_advert'=>$order['final_price']],['id'=>1]);
            return $resultMemberData;
        }catch (Exception $e){
            Yii::error("[order_delivery]".$e->getMessage());
            return false;
        }

    }
}
