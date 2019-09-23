<?php

namespace console\models;


use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignMemberCount extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_member_count}}';
    }

    # 初始化签到到数据
    public function initSignData() {
        $date = date("Y-m-d");
        $memberModel = SignTeamMember::find()->joinWith('team',false)->select('team_type,yl_sign_team_member.team_id,yl_sign_team_member.member_id')->asArray()->all();
        if (empty($memberModel)) {
            return true;
        }
        $countModel = new SignMemberCount();
        foreach ($memberModel as $member) {
            try{
                $cloneModel = clone $countModel;
                $cloneModel->team_type = $member['team_type'];
                $cloneModel->team_id = $member['team_id'];
                $cloneModel->member_id = $member['member_id'];
                $cloneModel->update_at = '0000-00-00 00:00:00';
                $cloneModel->create_at = $date;
                $cloneModel->save();
            } catch (\Exception $e) {
                \Yii::error($e->getMessage());
                ToolsClass::printLog('init_sign_data',$e->getMessage());
            }
        }
    }

    # 获取未签到成员和未达标成员
    public function getMemberCountData($countDate) {
        # 未签到成员
        $noSignMember = [];
        # 未达标成员
        $noQualifiedMember = [];
        # 超时签到成员
        $lateSignMember = [];
        # 已签到成员
        $signMember = [];
        # 早退成员
        $leaveEarlyMember = [];
        # 所有的团队
        $teamData = ['business' => [],'maintain' => []];
        $memberModel = self::find()->select('leave_early,member_id,team_id,late_sign,sign_number,sign_number,qualified,team_type')->where(['and',['create_at' => $countDate]])->asArray()->all();
        if (empty($memberModel)) {
            return [$lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$teamData,$leaveEarlyMember];
        }
        foreach ($memberModel as $member) {
            if ($member['team_type'] == 1) {
                $teamData['business'][] = $member['team_id'];
            } else {
                $teamData['maintain'][] = $member['team_id'];
            }
            if ($member['late_sign'] == 1) {
                $lateSignMember[$member['team_id']][] = $member['member_id'];
            }
            if ($member['qualified'] == 0) {
                $noQualifiedMember[$member['team_id']][] = $member['member_id'];
            }
            if ($member['leave_early'] == 1) {
                $leaveEarlyMember[$member['team_id']][] = $member['member_id'];
            }
            if($member['sign_number'] == 0) {
                $noSignMember[$member['team_id']][] = $member['member_id'];
            } else {
                $signMember[$member['team_id']][] = $member['member_id'];
            }
        }
        return [$lateSignMember,$noSignMember,$noQualifiedMember,$signMember,$teamData,$leaveEarlyMember];
    }
}
