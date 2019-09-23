<?php

namespace cms\modules\account\models;

use cms\modules\shop\models\ShopApply;
use common\libs\ToolsClass;
use Yii;

/**
 * This is the model class for table "yl_member_order_reward".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $order_id 订单编号
 * @property string $order_create_at 订单创建时间
 * @property string $order_price 交易费用(分)
 * @property string $reward_price 奖励金
 * @property string $goods_name 购买的商品名称
 * @property string $external_shop_name 1818lao的店铺名称
 * @property string $external_shop_id 1818lao的店铺ID
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $area_name 所属地区
 * @property string $create_at 创建时间
 */
class MemberOrderReward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_order_reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'order_price', 'reward_price', 'external_shop_id', 'shop_id'], 'integer'],
            [['order_id', 'order_create_at', 'goods_name', 'external_shop_name', 'external_shop_id', 'shop_id', 'shop_name', 'area_name'], 'required'],
            [['order_create_at', 'create_at'], 'safe'],
            [['order_id'], 'string', 'max' => 30],
            [['goods_name', 'area_name'], 'string', 'max' => 255],
            [['external_shop_name', 'shop_name'], 'string', 'max' => 100],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'order_id' => 'Order ID',
            'order_create_at' => 'Order Create At',
            'order_price' => 'Order Price',
            'reward_price' => 'Reward Price',
            'goods_name' => 'Goods Name',
            'external_shop_name' => 'External Shop Name',
            'external_shop_id' => 'External Shop ID',
            'shop_id' => 'Shop ID',
            'shop_name' => 'Shop Name',
            'area_name' => 'Area Name',
            'create_at' => 'Create At',
        ];
    }
    public static function ExportCsv($data){
        foreach ($data as $k=>$v){
            $csv[$k]['id']=$v['id'];
            $csv[$k]['order_id']=$v['order_id'];
            $csv[$k]['create_at']=$v['create_at'];
            $csv[$k]['shop_id']=$v['shop_id'];
            $csv[$k]['shop_name']=$v['shop_name'];
            $csv[$k]['area_name']=$v['area_name'];
            $csv[$k]['apply_name']=$v['shopApply']['apply_name'];
            $csv[$k]['apply_mobile']=$v['shopApply']['apply_mobile'];
            $csv[$k]['order_price']=ToolsClass::priceConvert($v['order_price']);
            $csv[$k]['reward_price']=ToolsClass::priceConvert($v['reward_price']);
        }
        return $csv;
    }

    //关联shop_apply表获取法人姓名手机号
    public function getShopApply(){
       return  $this->hasOne(ShopApply::className(),['id'=>'shop_id'])->select('id,apply_name,apply_mobile');
    }
}
