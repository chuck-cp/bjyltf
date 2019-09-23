<?php

namespace console\models;

use Yii;

/**
 * 地区电费配置表
 */
class SystemZonePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_zone_price}}';
    }
}
