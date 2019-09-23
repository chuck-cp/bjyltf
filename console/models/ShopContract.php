<?php

namespace console\models;

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
 * @property int $status 合同状态(1、正常 2、作废)
 * @property string $create_at 创建时间
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
            [['shop_id', 'shop_type', 'examine_status', 'status'], 'integer'],
            [['create_at'], 'safe'],
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
            'contract_number' => 'Contract Number',
            'cabinet_number' => 'Cabinet Number',
            'receiver_name' => 'Receiver Name',
            'description' => 'Description',
            'examine_status' => 'Examine Status',
            'status' => 'Status',
            'create_at' => 'Create At',
        ];
    }
}
