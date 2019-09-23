<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_order_throw_list".
 *
 * @property string $id
 * @property string $throw_id 和order_throw表ID关联
 * @property string $order_id 订单ID
 */
class OrderThrowList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_throw_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['throw_id', 'order_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'throw_id' => 'Throw ID',
            'order_id' => 'Order ID',
        ];
    }
}
