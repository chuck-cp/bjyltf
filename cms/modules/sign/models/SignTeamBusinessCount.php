<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_team_business_count".
 *
 * @property string $id
 * @property int $team_id 团队ID
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
class SignTeamBusinessCount extends \yii\db\ActiveRecord
{
    public $total_sign_shop_number_sum;//总签到人数
    public $no_sign_member_number_sum;//未签到人数
    public $overtime_sign_member_number_sum;//超时签到的人数
    public $unqualified_member_number_sum;//未达标的人数
    public $repeat_sign_number_sum;//签到重复率
    public $repeat_shop_number_sum;//重复店铺数
    public $leave_early_number_sum;//早退成员数
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_team_business_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'create_at'], 'required'],
            [['team_id', 'total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_sign_shop_number', 'repeat_sign_number', 'repeat_shop_number'], 'integer'],
            [['repeat_sign_rate'], 'number'],
            [['create_at'], 'safe'],
            [['create_at', 'team_id'], 'unique', 'targetAttribute' => ['create_at', 'team_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'total_sign_member_number' => 'Total Sign Member Number',
            'overtime_sign_member_number' => 'Overtime Sign Member Number',
            'no_sign_member_number' => 'No Sign Member Number',
            'unqualified_member_number' => 'Unqualified Member Number',
            'total_sign_shop_number' => 'Total Sign Shop Number',
            'repeat_sign_number' => 'Repeat Sign Number',
            'repeat_sign_rate' => 'Repeat Sign Rate',
            'repeat_shop_number' => 'Repeat Shop Number',
            'create_at' => 'Create At',
        ];
    }

    public function getSignTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id'])->select('id, team_name, team_member_number');
    }
}
