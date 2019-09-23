<?php

namespace console\models;

use Yii;

/**
 * 订单状态改变消息
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

    public function saveMessage($order_id,$status=5){
        $this->order_id = $order_id;
        $this->type = 2;
        if($status == 4){
            $this->desc = "广告已被投放";
        }elseif($status == 5){
            $this->desc = "广告投放结束";
        }elseif($status == 2){
            $this->type = 1;
            $this->desc = "已过尾款有效期";
        }
    }

}
