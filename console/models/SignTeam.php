<?php

namespace console\models;


use yii\db\ActiveRecord;

class SignTeam extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team}}';
    }

    // 获取签到的达标数量
    public static function getSignQualifiedNumber() {
        return SignTeam::find()->select('team_id,sign_qualified_number')->indexBy('team_id')->asArray()->all();
    }

    // 获取团队数量
    public static function getTeamData() {
        $result = ['business' => [],'maintain' => []];
        $teamModel = self::find()->select('id,team_type')->asArray()->all();
        if (empty($teamModel)) {
            return $result;
        }
        foreach ($teamModel as $team) {
            if ($team['team_type'] == 1) {
                $result['business'][] = $team['id'];
            } else {
                $result['maintain'][] = $team['id'];
            }
        }
        return $result;
    }
}
