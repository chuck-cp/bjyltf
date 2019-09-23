<?php

namespace cms\modules\sign\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "yl_sign_team".
 *
 * @property string $id
 * @property int $team_member_number 团队成员数量
 * @property int $team_manager_number 团队负责人数量
 * @property int $sign_interval_time 签到间隔时间(分钟)
 * @property int $sign_qualified_number 签到达标数量
 * @property string $first_sign_time 首次签到时间
 * @property string $team_name 团队名称
 * @property int $team_type 团队类型(1、业务 2、维护)
 * @property string $team_member_id 创建人的ID
 */
class SignTeam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_member_number', 'team_manager_number', 'sign_interval_time', 'sign_qualified_number', 'team_type', 'team_member_id'], 'integer'],
            [['first_sign_time', 'team_name', 'team_member_id'], 'required'],
            [['first_sign_time'], 'string', 'max' => 10],
            [['team_name'], 'string', 'max' => 30],
            [['earliest_closing_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'team_member_number' => '成员数量',
            'team_manager_number' => '负责人数量',
            'sign_interval_time' => '签到间隔时间',
            'sign_qualified_number' => '签到达标数量',
            'first_sign_time' => '首次签到时间',
            'team_name' => '团队名称',
            'team_type' => '团队类型',
            'team_member_id' => '创建人的ID',
            'team_member_name' => '管理员',
            'create_at' => '创建时间',
        ];
    }
    public static function signTeam ($team_type,$sign_team=0){
        if($sign_team!=0){
            $memberTeamModel = self::find()->where(['team_type'=>$team_type,'id'=>$sign_team])->select('id,team_name')->asArray()->all();
        }else{
            $memberTeamModel = self::find()->where(['team_type'=>$team_type])->select('id,team_name')->asArray()->all();
        }
        $memberTeamModel = ArrayHelper::map($memberTeamModel,'id','team_name');
        return $memberTeamModel;
    }
}
