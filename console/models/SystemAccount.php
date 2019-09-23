<?php

namespace console\models;

use Yii;

/**
 * 收入日志
 */
class SystemAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_account}}';
    }
}
