<?php

namespace cms\modules\sign\models;

use cms\modules\member\models\Member;
use Yii;

/**
 * This is the model class for table "yl_sign_team_count_member_detail".
 *
 * @property string $id
 * @property int $team_id 团队ID
 * @property string $team_name 团队名称
 * @property int $team_type 团队类型(1、业务组 2、维护组)
 * @property int $team_member_type 用户在团队中的职位(1、普通成员 2、负责人 3、管理人)
 * @property string $member_id 成员ID
 * @property int $member_type 成员类别(1、超时签到人数 2、未达标人数 3、未签到人数 4、中评人数 5、差评人数)
 * @property string $create_at 统计日期
 */
class SignTeamCountMemberDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_team_count_member_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'team_name', 'team_type', 'create_at'], 'required'],
            [['team_id', 'team_type', 'team_member_type', 'member_id', 'member_type'], 'integer'],
            [['create_at'], 'safe'],
            [['team_name'], 'string', 'max' => 50],
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
            'team_name' => '团队名称',
            'team_type' => 'Team Type',
            'team_member_type' => 'Team Member Type',
            'member_id' => 'Member ID',
            'member_type' => 'Member Type',
            'create_at' => '签到日期',
        ];
    }

    //获取成员姓名和电话
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
    //获取该成员所在团队信息
    public function getSignTeam(){
        return $this->hasOne(SignTeam::className(),['id'=>'team_id']);
    }
    //获取团队成员信息
    public function getSignTeamMember(){
        return $this->hasOne(SignTeamMember::className(),['member_id'=>'member_id']);
    }

}
