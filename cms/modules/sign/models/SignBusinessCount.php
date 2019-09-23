<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_business_count".
 *
 * @property string $id
 * @property int $total_sign_member_number 总签到人数
 * @property int $overtime_sign_member_number 超时签到的人数
 * @property int $no_sign_member_number 未签到人数
 * @property int $unqualified_member_number 未达标的人数
 * @property int $total_sign_shop_number 店铺总签到数
 * @property int $repeat_sign_number 店铺重复签到数
 * @property double $repeat_sign_rate 签到重复率
 * @property int $repeat_shop_number 重复店铺数
 * @property string $create_at 统计日期
 */
class SignBusinessCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_business_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number','leave_early_number', 'total_sign_shop_number', 'repeat_sign_number', 'repeat_shop_number'], 'integer'],
            [['repeat_sign_rate'], 'number'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'total_sign_member_number' => '总签到人数',
            'overtime_sign_member_number' => '超时签到的人数',
            'no_sign_member_number' => '未签到人数',
            'unqualified_member_number' => '未达标的人数',
            'leave_early_number' => '早退成员的人数',
            'total_sign_shop_number' => '店铺总签到数',
            'repeat_sign_number' => '店铺重复签到数',
            'repeat_sign_rate' => '签到重复率',
            'repeat_shop_number' => '重复店铺数',
            'create_at' => '统计日期',
        ];
    }
}
