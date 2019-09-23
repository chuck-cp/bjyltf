<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%order_throw_program_list}}".
 *
 * @property string $id
 * @property integer $throw_id
 * @property integer $order_id
 * @property integer $time
 */
class RedisSpaceRate extends \yii\db\ActiveRecord
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
        return '{{%redis_space_rate}}';
    }
}
