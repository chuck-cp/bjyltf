<?php

namespace cms\modules\sign\models;

use Yii;
use cms\modules\member\models\Member;
use cms\modules\sign\models\SignTeam;
/**
 * This is the model class for table "yl_sign_member_count".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property string $member_id 成员ID
 * @property int $late_sign 是否超时签到(1、是)
 * @property int $qualified 是否达标(1、是)
 * @property int $sign_number 签到数量
 * @property string $update_at 上次签到的时间
 * @property string $create_at 统计日期
 */
class SignMemberCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_member_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'member_id', 'late_sign', 'qualified', 'sign_number'], 'integer'],
            [['update_at', 'create_at'], 'safe'],
            [['create_at'], 'required'],
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
            'member_id' => 'Member ID',
            'late_sign' => 'Late Sign',
            'qualified' => 'Qualified',
            'sign_number' => 'Sign Number',
            'update_at' => 'Update At',
            'create_at' => 'Create At',
        ];
    }
    //获取人员电话和姓名
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id, name,mobile');
    }

    //获取团队信息
    public function getSignTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id'])->select('id,team_name');
    }

    //获取职务
    public function getMemberType(){
        return $this->hasOne(SignTeamMember::className(),['member_id'=>'member_id'])->select('id,member_id,member_type');
    }
}
