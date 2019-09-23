<?php

namespace console\models;

use Yii;

class OrderThrowProgramDetail extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    public static function tableName()
    {
        return '{{%screen_run_time}}';
    }
}
