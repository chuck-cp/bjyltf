<?php

namespace console\models;

use common\libs\ToolsClass;
use Yii;
use yii\db\ActiveRecord;

/**
 * 流水动态
 */
class MemberAccountMessage extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%member_account_message}}';
    }

}
