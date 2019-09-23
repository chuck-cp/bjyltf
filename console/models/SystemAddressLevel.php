<?php

namespace console\models;

use Yii;

/**
 * 地区等级
 *
 */
class SystemAddressLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_address_level}}';
    }




}
