<?php

namespace console\models;

use Yii;

/**
 * 业务员收入
 */
class MemberAccountCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account_count}}';
    }
}
