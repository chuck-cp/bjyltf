<?php

namespace cms\modules\schedules\models;

use Yii;

/**
 * This is the model class for table "yl_system_test_shop".
 *
 * @property int $shop_id 店铺ID
 * @property string $area_id 地区ID
 */
class SystemTestShop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_test_shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'area_id'], 'required'],
            [['shop_id', 'area_id'], 'integer'],
            [['shop_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shop_id' => 'Shop ID',
            'area_id' => 'Area ID',
        ];
    }
}
