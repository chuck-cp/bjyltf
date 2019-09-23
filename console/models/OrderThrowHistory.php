<?php

namespace console\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * 订单
 */
class OrderThrowHistory extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%order_throw_history}}';
    }

    public function attributes()
    {
        return ["_id","order_id","shop_id","area_id","throw_number","software_number","is_give"];
    }
}
