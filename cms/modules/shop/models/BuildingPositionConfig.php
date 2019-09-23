<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_building_position_config".
 *
 * @property string $id
 * @property string $parent_id 上级ID
 * @property int $shop_type 场景类型(1、写字楼 2、 公园 3、商住两用)
 * @property int $screen_type 设备类型(1、LED 2、画框)
 * @property string $position_name 位置名称
 * @property int $mark 标记
 */
class BuildingPositionConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_building_position_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'shop_type', 'screen_type', 'mark'], 'integer'],
            [['position_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'shop_type' => 'Shop Type',
            'screen_type' => 'Screen Type',
            'position_name' => 'Position Name',
            'mark' => 'Mark',
        ];
    }

    public static function getPositionName($id){
        $model = self::findOne(['id'=>$id]);
        if($model){
            return $model->position_name;
        }
        return '';
    }
}
