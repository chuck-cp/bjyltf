<?php

namespace console\models;

use Yii;


class ShopHeadquartersList extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_headquarters_list}}';
    }
}
