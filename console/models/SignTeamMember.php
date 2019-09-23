<?php

namespace console\models;


use yii\db\ActiveRecord;

class SignTeamMember extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sign_team_member}}';
    }

    public function getTeam() {
        return $this->hasOne(SignTeam::className(),['id' => 'team_id']);
    }
}
