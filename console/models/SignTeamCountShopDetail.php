<?php

namespace console\models;


use yii\db\ActiveRecord;

class SignTeamCountShopDetail extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team_count_shop_detail}}';
    }
}
