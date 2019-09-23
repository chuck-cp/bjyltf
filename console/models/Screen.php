<?php

namespace console\models;

use Yii;

/**
 * 屏幕管理
 */
class Screen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%screen}}';
    }

}
