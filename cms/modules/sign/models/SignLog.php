<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_log".
 *
 * @property string $id
 * @property string $member_id 操作人ID
 * @property string $member_name 操作人姓名
 * @property string $team_id 团队ID
 * @property string $team_name 团队名称
 * @property int $team_type 团队类型(1、业务 2、维护)
 * @property string $content 操作内容
 * @property string $create_at 创建时间
 */
class SignLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'team_id', 'team_type'], 'integer'],
            [['member_name', 'team_name', 'content'], 'required'],
            [['create_at'], 'safe'],
            [['member_name', 'team_name'], 'string', 'max' => 30],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'member_name' => 'Member Name',
            'team_id' => 'Team ID',
            'team_name' => 'Team Name',
            'team_type' => 'Team Type',
            'content' => 'Content',
            'create_at' => 'Create At',
        ];
    }
}
