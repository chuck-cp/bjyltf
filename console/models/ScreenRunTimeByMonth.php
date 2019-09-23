<?php

namespace console\models;

use Yii;

class ScreenRunTimeByMonth extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%screen_run_time_by_month}}';
    }
}
