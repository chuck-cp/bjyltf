<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%shop_screen_replace_list}}".
 *
 * @property int $id
 * @property int $replace_id screen_replace表的主键
 * @property string $device_number 设备编号
 * @property string $replace_device_number 替换后的设备编号
 * @property string $replace_desc 更换理由
 */
class ShopScreenReplaceList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_screen_replace_list}}';
    }

}
