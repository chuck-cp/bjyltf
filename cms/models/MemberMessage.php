<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%member_message}}".
 *
 * @property string $id 用户ID
 * @property string $member_id 下级的ID
 * @property string $notice yl_system_notice表的id
 * @property string $title 伙伴的等级
 * @property string $content
 * @property int $message_type 消息类型(1、公告 2、系统消息)
 * @property int $status 状态(1、已读 0、未读)
 * @property string $create_at 创建时间
 */
class MemberMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'title', 'message_type'], 'required'],
            [['member_id', 'notice_id', 'message_type', 'status'], 'integer'],
            [['content'], 'string'],
            [['create_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'notice_id' => 'Notice Id',
            'title' => 'Title',
            'content' => 'Content',
            'message_type' => 'Message Type',
            'status' => 'Status',
            'create_at' => 'Create At',
        ];
    }
}
