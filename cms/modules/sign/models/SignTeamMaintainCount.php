<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_team_maintain_count".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property int $total_sign_number 总签到数量
 * @property int $total_sign_member_number 总签到人数
 * @property int $overtime_sign_member_number 超时签到的人数
 * @property int $no_sign_member_number 未签到人数
 * @property int $unqualified_member_number 未达标的人数
 * @property int $total_evaluate_number 总评价数量
 * @property int $good_evaluate_number 好评数量
 * @property int $middle_evaluate_number 中评数量
 * @property int $bad_evaluate_number 差评数量
 * @property double $bad_evaluate_rate 坏评率
 * @property string $create_at 统计日期
 */
class SignTeamMaintainCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $total_sign_number_sum;//总签到数量求和
    public $no_sign_member_number_sum;//未签到成员求和
    public $overtime_sign_member_number_sum;//超时签到数求和
    public $unqualified_member_number_sum;//未达标的人数求和
    public $good_evaluate_number_sum;//好评求和
    public $middle_evaluate_number_sum;//中评求和
    public $bad_evaluate_number_sum;//差评求和
    public $total_evaluate_number_sum;//总评价求和
    public $leave_early_number_sum;//早退成员数

    public static function tableName()
    {
        return 'yl_sign_team_maintain_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'total_sign_number', 'total_sign_member_number', 'overtime_sign_member_number', 'no_sign_member_number', 'unqualified_member_number', 'total_evaluate_number', 'good_evaluate_number', 'middle_evaluate_number', 'bad_evaluate_number'], 'integer'],
            [['bad_evaluate_rate'], 'number'],
            [['create_at'], 'required'],
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
            'total_sign_number' => 'Total Sign Number',
            'total_sign_member_number' => 'Total Sign Member Number',
            'overtime_sign_member_number' => 'Overtime Sign Member Number',
            'no_sign_member_number' => 'No Sign Member Number',
            'unqualified_member_number' => 'Unqualified Member Number',
            'total_evaluate_number' => 'Total Evaluate Number',
            'good_evaluate_number' => 'Good Evaluate Number',
            'middle_evaluate_number' => 'Middle Evaluate Number',
            'bad_evaluate_number' => 'Bad Evaluate Number',
            'bad_evaluate_rate' => 'Bad Evaluate Rate',
            'create_at' => 'Create At',
        ];
    }
    public function getMaintainTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id'])->select('id, team_name, team_member_number');
    }
}
