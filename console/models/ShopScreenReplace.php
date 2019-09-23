<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%shop_screen_replace}}".
 *
 * @property int $id
 * @property int $shop_id 店铺ID
 * @property string $shop_name 店铺名称
 * @property string $shop_address 店铺所在地址
 * @property int $install_team_id 指派团队ID
 * @property int $install_member_id 安装人ID
 * @property string $install_member_name 安装人姓名
 * @property string $install_finish_at 安装完成时间
 * @property int $replace_screen_number 申请更换的屏幕数量
 * @property int $create_user_id 申请人ID
 * @property string $create_user_name 申请人姓名
 * @property int $status 状态(0.申请更换，1.待安装(指派)，2.待审核，3.审核未通过，4.换屏完成)
 * @property string $create_at 创建时间
 * @property string $assign_at 指派时间
 */
class ShopScreenReplace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_screen_replace}}';
    }

}
