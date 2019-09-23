<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%member_equipment}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $token 密钥
 * @property string $equipment_number 设备编号
 * @property int $equipment_type 设备类型(1、安卓 2、IOS)
 * @property string $push_id
 * @property int $push_status 是否推送(1、推送 2、不推送)
 */
class MemberEquipment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_equipment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'token', 'equipment_number', 'push_id'], 'required'],
            [['member_id', 'equipment_type', 'push_status'], 'integer'],
            [['token', 'equipment_number'], 'string', 'max' => 32],
            [['push_id'], 'string', 'max' => 25],
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
            'token' => 'Token',
            'equipment_number' => 'Equipment Number',
            'equipment_type' => 'Equipment Type',
            'push_id' => 'Push ID',
            'push_status' => 'Push Status',
        ];
    }
}
