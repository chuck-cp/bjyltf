<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%order_message}}".
 *
 * @property string $id 主键
 * @property int $order_id yl_order 的主键
 * @property int $type 类型 1 ： 付款状态  2：投放状态
 * @property string $desc 操作说明
 * @property string $reject_reason 驳回原因
 * @property string $create_at 添加时间
 */
class OrderMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'type'], 'integer'],
            [['create_at'], 'safe'],
            [['desc'], 'string', 'max' => 50],
            [['reject_reason'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'type' => 'Type',
            'desc' => 'Desc',
            'reject_reason' => 'Reject Reason',
            'create_at' => 'Create At',
        ];
    }

    public static function Log($order_id,$desc,$type=1){
        $model = new OrderMessage();
        $model->order_id = $order_id;
        $model->desc = $desc;
        $model->type = $type;
        return $model->save();
    }
}
