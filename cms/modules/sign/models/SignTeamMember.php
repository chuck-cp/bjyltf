<?php

namespace cms\modules\sign\models;

use cms\modules\member\models\Member;
use Yii;

/**
 * This is the model class for table "yl_sign_team_member".
 *
 * @property string $id
 * @property int $team_id 团队ID
 * @property string $member_id 成员ID
 * @property int $member_type 成员类别(1、普通成员 2、负责人 3、管理人)
 * @property string $update_at 设置为小组负责人的时间
 */
class SignTeamMember extends \yii\db\ActiveRecord
{
    public $sign_numbers;
    public $late_signs;
    public $create_at;
    public $create_at_end;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_team_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'member_id'], 'required'],
            [['team_id', 'member_id', 'member_type'], 'integer'],
            [['update_at'], 'safe'],
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
            'member_type' => 'Member Type',
            'update_at' => 'Update At',
        ];
    }

    public static function getDuthByType($member_type){
        $array = [
            '1' => '普通成员',
            '2' => '负责人',
            '3' => '管理人',
        ];
        if(array_key_exists($member_type,$array)){
            return $array[$member_type];
        }else{
            return '---';
        }
    }

    //获取人员电话和姓名
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id'])->select('id, name, mobile');
    }
    //获取成员的签到数据
    public function getSignMemberCount(){
        return $this->hasMany(SignMemberCount::className(),['member_id'=>'member_id']);
    }
}
