<?php

namespace console\models;

use Yii;

/**
 * 广告类型
 */
class AdvertPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advert_position}}';
    }
}
