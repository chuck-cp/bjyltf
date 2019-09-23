<?php
namespace console\models;

use Yii;

class ShopAdvertImage extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_advert_image}}';
    }

}