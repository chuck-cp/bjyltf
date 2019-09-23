<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%member_area_count}}".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $area 区域ID
 * @property string $shop_number 店铺数量
 * @property string $screen_number 屏幕数量
 */
class MemberAreaCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_area_count}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'area', 'shop_number', 'screen_number'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'area' => 'Area',
            'shop_number' => 'Shop Number',
            'screen_number' => 'Screen Number',
        ];
    }
}
