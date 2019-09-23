<?php

namespace cms\modules\withdraw\models;

use Yii;

/**
 * This is the model class for table "{{%member_account_message}}".
 *
 * @property string $id 用户ID
 * @property string $member_id 用户ID
 * @property string $title 消息描述
 * @property string $create_at 创建时间
 */
class MemberAccountMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'title'], 'required'],
            [['member_id'], 'integer'],
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
            'title' => 'Title',
            'create_at' => 'Create At',
        ];
    }
}
