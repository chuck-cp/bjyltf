<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_member_install_history".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property string $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $replace_id 更换屏幕的ID
 * @property string $area_name 店铺所在地区
 * @property string $address 店铺详细地址
 * @property int $screen_number 屏幕数量
 * @property int $type 类型(1、安装 2、更换)
 * @property string $create_at 创建日期
 * @property string $shop_image 店铺门脸
 */
class MemberInstallHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_install_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'shop_id', 'replace_id', 'screen_number', 'type'], 'integer'],
            [['shop_id', 'shop_name', 'area_name', 'address', 'shop_image'], 'required'],
            [['create_at'], 'safe'],
            [['shop_name', 'area_name', 'address'], 'string', 'max' => 100],
            [['shop_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'shop_id' => 'Shop ID',
            'shop_name' => 'Shop Name',
            'replace_id' => 'Replace ID',
            'area_name' => 'Area Name',
            'address' => 'Address',
            'screen_number' => 'Screen Number',
            'type' => 'Type',
            'create_at' => 'Create At',
            'shop_image' => 'Shop Image',
        ];
    }
}
