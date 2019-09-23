<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "{{%order_cannel}}".
 *
 * @property string $order_id 订单ID
 * @property string $cancel_cause 订单取消原因
 * @property string $create_at 取消时间
 */
class OrderCannel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_cannel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['create_at'], 'safe'],
            [['cancel_cause'], 'string', 'max' => 100],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'cancel_cause' => 'Cancel Cause',
            'create_at' => 'Create At',
        ];
    }
}
