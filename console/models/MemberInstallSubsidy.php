<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%member_install_subsidy}}".
 *
 * @property int $id
 * @property int $install_member_id 安装人用户ID
 * @property int $install_shop_number 今日安装店铺数量
 * @property int $install_screen_number 今日安装屏幕数量
 * @property int $assign_shop_number 指派的店铺数量
 * @property int $assign_screen_number 指派的屏幕数量
 * @property int $income_price 今日的收入(分)
 * @property int $subsidy_price 今日补贴金额(分)
 * @property string $create_at 日期
 */
class MemberInstallSubsidy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_install_subsidy}}';
    }
}
