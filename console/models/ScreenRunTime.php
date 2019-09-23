<?php

namespace console\models;

use Yii;

class ScreenRunTime extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%screen_run_time}}';
    }
}
