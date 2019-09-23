<?php

namespace console\models;

use Yii;

/**
 * 收入日志
 */
class LogAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_account}}';
    }
}
