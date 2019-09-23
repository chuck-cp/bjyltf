<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_maintain_count".
 *
 * @property string $id
 * @property int $total_sign_member_number 总签到人数
 * @property int $overtime_sign_member_number 超时签到的人数
 * @property int $no_sign_member_number 未签到人数
 * @property int $unqualified_member_number 未达标的人数
 * @property int $total_evaluate_number 总评价数量
 * @property int $good_evaluate_number 好评数量
 * @property int $middle_evaluate_number 中评数量
 * @property int $bad_evaluate_number 差评数量
 * @property double $bad_evaluate_rate 差评率
 * @property string $create_at 统计日期
 */
class SignMaintainCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_maintain_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_evaluate_number', 'good_evaluate_number', 'middle_evaluate_number', 'bad_evaluate_number','total_sign_number'], 'integer'],
            [['bad_evaluate_rate'], 'number'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['create_at'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_sign_number'=>'签到总数',
            'total_sign_member_number' => '总签到人数',
            'overtime_sign_member_number' => '超时签到的人数',
            'no_sign_member_number' => '未签到人数',
            'unqualified_member_number' => '未达标的人数',
            'total_evaluate_number' => '总评价数量',
            'good_evaluate_number' => '好评数量',
            'middle_evaluate_number' => '中评数量',
            'bad_evaluate_number' => '差评数量',
            'bad_evaluate_rate' => '差评率',
            'create_at' => '统计日期',
            'leave_early_number' => '早退成员数',
        ];
    }
}
