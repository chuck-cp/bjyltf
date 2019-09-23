<?php

namespace console\models;

use Yii;

/**
 * 订单地区
 */
class OrderArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_area}}';
    }
}
