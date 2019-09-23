<?php

namespace console\models;


use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignTeamMaintainCount extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team_maintain_count}}';
    }

    // 保存签到数据
    public function saveSignCountData($signCountData,$create_at) {
        try {
            if (empty($signCountData)) {
                return true;
            }
            foreach ($signCountData as $team_id => $countData) {
                $total_sign_number = $countData['total_sign_number'];
                $total_sign_member_number = $countData['total_sign_member_number'];
                $overtime_sign_member_number = $countData['overtime_sign_member_number'];
                $no_sign_member_number = $countData['no_sign_member_number'];
                $unqualified_member_number = $countData['unqualified_member_number'];
                $leave_early_number = $countData['leave_early_number'];
                \Yii::$app->db->createCommand("insert into yl_sign_team_maintain_count (team_id,total_sign_member_number,overtime_sign_member_number,no_sign_member_number,unqualified_member_number,total_sign_number,leave_early_number,create_at) values ({$team_id},{$total_sign_member_number},{$overtime_sign_member_number},{$no_sign_member_number},{$unqualified_member_number},{$total_sign_number},{$leave_early_number},'{$create_at}') ON DUPLICATE KEY UPDATE total_sign_member_number = {$total_sign_member_number},overtime_sign_member_number = {$overtime_sign_member_number},no_sign_member_number = {$no_sign_member_number},unqualified_member_number = {$unqualified_member_number},total_sign_number = {$total_sign_number},leave_early_number = {$leave_early_number}")->execute();
            }
            return true;
        } catch (\Exception $e) {
            ToolsClass::printLog('save_sign_count_data',$e->getMessage());
            \Yii::error($e->getMessage());
            return false;
        }
    }
}
