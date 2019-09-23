<?php

namespace console\models;

use Yii;

class MemberInstallHistory extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%member_install_history}}';
    }
}
