<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_building_shop_screen".
 *
 * @property string $id
 * @property string $shop_id building_shop表ID
 * @property string $position_different_id building_shop_position_different表的ID
 * @property string $position_config_id building_position_config表的二级位置的ID
 * @property string $position_name 位置名称
 * @property string $image_url 设备图片
 * @property string $device_number 设备编号
 */
class BuildingShopScreen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_shop_screen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'position_different_id', 'position_config_id'], 'integer'],
            [['image_url', 'device_number'], 'required'],
            [['position_name'], 'string', 'max' => 20],
            [['image_url'], 'string', 'max' => 255],
            [['device_number'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'position_different_id' => 'Position Different ID',
            'position_config_id' => 'Position Config ID',
            'position_name' => 'Position Name',
            'image_url' => 'Image Url',
            'device_number' => 'Device Number',
        ];
    }
}
