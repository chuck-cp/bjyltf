<?php

namespace console\models;

use Yii;

class MemberShopApplyCount extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%member_shop_apply_count}}';
    }
}
