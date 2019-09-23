<?php

namespace cms\modules\examine\models;

use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use Yii;

/**
 * This is the model class for table "yl_shop_contract".
 *
 * @property string $id
 * @property int $shop_id 总部ID或店铺ID
 * @property int $shop_type 店铺类型(1、自营、租赁 2、连锁店)
 * @property string $contract_number 合同编号
 * @property string $cabinet_number 柜子编号
 * @property string $receiver_name 接收人姓名
 * @property string $description 备注
 * @property int $examine_status 审核状态(0、未审核 1、通过 2、驳回)
 */
class ShopContract extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_shop_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_type', 'examine_status'], 'integer'],
            [['contract_number'], 'string', 'max' => 50],
            [['cabinet_number', 'receiver_name'], 'string', 'max' => 10],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'shop_type' => 'Shop Type',
            'contract_number' => '合同编号',
            'cabinet_number' => '柜子编号',
            'receiver_name' => '接收人姓名',
            'description' => '备注',
            'examine_status' => '审核状态',
            'create_at' => '签订时间',
        ];
    }

    //店铺信息
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }
    //店铺法人信息
    public function getShopApply(){
        return $this->hasOne(ShopApply::className(),['id'=>'shop_id']);
    }
    //总部法人信息
    public function getHeadquarters(){
        return $this->hasOne(ShopHeadquarters::className(),['id'=>'shop_id']);
    }

    //合同状态
    public static function getContractStatus($num)
    {
        $srr = [
            '0'=>'待审核',
            '1'=>'审核通过',
            '2'=>'审核驳回',
        ];
        return array_key_exists($num,$srr) ? $srr[$num] : '---';
    }
}
