<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%member_install_subsidy_list}}".
 *
 * @property int $id
 * @property int $subsidy_id install_subidy表的ID
 * @property int $subsidy_price 补贴金额(分)
 * @property string $subisdy_desc 补贴理由
 * @property int $create_user_id 补贴人ID
 * @property string $create_user_name 补贴人姓名
 * @property string $create_at 补贴时间
 */
class MemberInstallSubsidyList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_install_subsidy_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'subisdy_desc', 'create_user_name'], 'required'],
            [['id', 'subsidy_id', 'subsidy_price', 'create_user_id'], 'integer'],
            [['create_at'], 'safe'],
            [['subisdy_desc'], 'string', 'max' => 100],
            [['create_user_name'], 'string', 'max' => 20],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subsidy_id' => 'Subsidy ID',
            'subsidy_price' => 'Subsidy Price',
            'subisdy_desc' => 'Subisdy Desc',
            'create_user_id' => 'Create User ID',
            'create_user_name' => 'Create User Name',
            'create_at' => 'Create At',
        ];
    }
}
