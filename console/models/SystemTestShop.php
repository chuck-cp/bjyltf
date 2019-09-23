<?php

namespace console\models;
use yii\db\ActiveRecord;

class SystemTestShop extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%system_test_shop}}';
    }
}