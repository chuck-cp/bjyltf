<?php

namespace console\models;


use common\libs\ToolsClass;
use yii\db\ActiveRecord;

class SignTeamCountMemberDetail extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team_count_member_detail}}';
    }

    public static function writeCountDetail($team_id,$team_type,$member_type,$data,$create_at)
    {
        try {
            $detailModel = new SignTeamCountMemberDetail();
            foreach ($data as $key => $value) {
                $cloneModel = clone $detailModel;
                $cloneModel->team_id = $team_id;
                $cloneModel->team_type = $team_type;
                $cloneModel->member_id = $value;
                $cloneModel->member_type = $member_type;
                $cloneModel->create_at = $create_at;
                $cloneModel->save();
            }
            return true;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            ToolsClass::printLog('write_count_detail',$e->getMessage());
            return false;
        }
    }
}
