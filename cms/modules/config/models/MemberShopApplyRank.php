<?php

namespace cms\modules\config\models;

use Yii;

/**
 * This is the model class for table "yl_member_shop_apply_rank".
 *
 * @property string $id
 * @property string $member_id 用户ID
 * @property int $last_half_past_month_shop_number 上半月联系店铺的数量
 * @property string $last_half_past_month_screen_number 上半月联系屏幕的数量
 * @property int $last_week_shop_number 上周联系的店铺数量
 * @property string $last_week_screen_number 上周联系屏幕的数量
 * @property int $week_shop_number 本周联系的店铺数量
 * @property string $week_screen_number 本周联系的屏幕数量
 * @property int $month_shop_number 本月联系的店铺数量
 * @property string $month_screen_number 本月联系屏幕的数量
 * @property int $last_month_shop_number 上月联系的店铺数量
 * @property string $last_month_screen_number 上月联系屏幕的数量
 * @property int $count_shop_number 联系的店铺总数量
 * @property string $count_screen_number 联系屏幕的总数量
 * @property int $wait_install_shop_number 待安装的店铺数量
 * @property string $wait_install_screen_number 待安装的屏幕数量
 */
class MemberShopApplyRank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_member_shop_apply_rank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'last_half_past_month_shop_number', 'last_half_past_month_screen_number', 'last_week_shop_number', 'last_week_screen_number', 'week_shop_number', 'week_screen_number', 'month_shop_number', 'month_screen_number', 'last_month_shop_number', 'last_month_screen_number', 'count_shop_number', 'count_screen_number', 'wait_install_shop_number', 'wait_install_screen_number'], 'integer'],
            [['member_id'], 'unique'],
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
            'last_half_past_month_shop_number' => 'Last Half Past Month Shop Number',
            'last_half_past_month_screen_number' => 'Last Half Past Month Screen Number',
            'last_week_shop_number' => 'Last Week Shop Number',
            'last_week_screen_number' => 'Last Week Screen Number',
            'week_shop_number' => 'Week Shop Number',
            'week_screen_number' => 'Week Screen Number',
            'month_shop_number' => 'Month Shop Number',
            'month_screen_number' => 'Month Screen Number',
            'last_month_shop_number' => 'Last Month Shop Number',
            'last_month_screen_number' => 'Last Month Screen Number',
            'count_shop_number' => 'Count Shop Number',
            'count_screen_number' => 'Count Screen Number',
            'wait_install_shop_number' => 'Wait Install Shop Number',
            'wait_install_screen_number' => 'Wait Install Screen Number',
        ];
    }
}
