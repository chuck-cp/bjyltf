<?php

namespace console\models;

use Yii;

class OrderPlayViewDate extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_play_view_date}}';
    }
}
