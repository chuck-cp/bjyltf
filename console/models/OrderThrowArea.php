<?php

namespace console\models;

use Yii;

/**
 * 订单
 */
class OrderThrowArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_throw_area}}';
    }
}
