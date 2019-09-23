<?php

namespace cms\modules\account\models;

use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_member_reward_detail".
 *
 * @property string $id
 * @property string $reward_member_id reward_member表的ID
 * @property string $member_id 用户ID
 * @property string $reward_price 奖励金
 * @property string $order_id 订单编号
 * @property string $order_price 订单金额
 * @property string $finish_at 订单完成时间
 * @property string $create_at 订单创建时间
 */
class MemberRewardDetail extends \yii\db\ActiveRecord
{
    public $did;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_reward_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reward_member_id', 'member_id', 'reward_price', 'order_price'], 'integer'],
            [['order_id'], 'required'],
            [['finish_at', 'create_at'], 'safe'],
            [['order_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reward_member_id' => 'Reward Member ID',
            'member_id' => 'Member ID',
            'reward_price' => 'Reward Price',
            'order_id' => 'Order ID',
            'order_price' => 'Order Price',
            'finish_at' => 'Finish At',
            'create_at' => 'Create At',
        ];
    }
    public function getRewardMember(){
        return $this->hasOne(MemberRewardMember::className(),['id'=>'reward_member_id']);
    }

    //获取店铺地区
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'search_shop_id'])->select('id,area,area_name');
    }

    //获取店铺的法人姓名法人手机号
    public function getShopApply(){
        return $this->hasOne(ShopApply::className(),['id'=>'search_shop_id'])->select('id,apply_name,apply_mobile');
    }

    //获取总部的信息
    public function getShopHeadquarters(){
        return $this->hasOne(ShopHeadquarters::className(),['id'=>'search_head_id'])->select('id,company_name,name,mobile,company_area_name,company_area_id');
    }

    public static function ExportCsv($data){
        foreach ($data as $k=>$v){
            $csv[$k]['id']=$v['id'];
            $csv[$k]['order_id']=$v['order_id'];
            $csv[$k]['create_at']=$v['create_at'];
            $csv[$k]['finish_at']=$v['finish_at'];
            $csv[$k]['shop_id']=$v['shop_id'];
            $csv[$k]['shop_name']=$v['rewardMember']['shop_name']?$v['rewardMember']['shop_name']:'--';
            $csv[$k]['company_name']=$v['shopHeadquarters']['company_name']?$v['shopHeadquarters']['company_name']:'--';
            if($v['search_head_id']==0){//地址
                $csv[$k]['area_name']=$v['shop']['area_name']?$v['shop']['area_name']:'--';
            }else{
                $csv[$k]['company_area_name']=$v['shopHeadquarters']['company_area_name']?str_replace(' &gt; ','',$v['shopHeadquarters']['company_area_name']):'--';//地
            }

            if($v['search_head_id']==0){//法人ID
                $csv[$k]['apply_name']=$v['shop']['shop_member_id']?$v['shop']['shop_member_id']:'--';
            }else{
                $csv[$k]['name']=$v['shopHeadquarters']['corporation_member_id']?$v['shopHeadquarters']['corporation_member_id']:'--';
            }

            if($v['search_head_id']==0){//法人姓名
                $csv[$k]['apply_name']=$v['shopApply']['apply_name']?$v['shopApply']['apply_name']:'--';
            }else{
                $csv[$k]['name']=$v['shopHeadquarters']['name']?$v['shopHeadquarters']['name']:'--';
            }

            if($v['search_head_id']==0){//法人手机
                $csv[$k]['apply_mobile']=$v['shopApply']['apply_mobile']?$v['shopApply']['apply_mobile']:'--';
            }else{
                $csv[$k]['mobile']=$v['shopHeadquarters']['mobile']?$v['shopHeadquarters']['mobile']:'--';
            }
            $csv[$k]['order_price']=ToolsClass::priceConvert($v['order_price']);
            $csv[$k]['reward_price']=ToolsClass::priceConvert($v['reward_price']);
            $csv[$k]['software_number']=$v['rewardMember']['software_number'];
        }
        return $csv;
    }



}
