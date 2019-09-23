<?php

namespace console\models;

use Yii;

class ShopApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_apply}}';
    }

}