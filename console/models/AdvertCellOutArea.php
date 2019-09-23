<?php

namespace console\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;


class AdvertCellOutArea extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    public static function tableName()
    {
        return '{{%advert_cell_out_area}}';
    }

}
