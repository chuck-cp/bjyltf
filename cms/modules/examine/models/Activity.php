<?php

namespace cms\modules\examine\models;

use Yii;

/**
 * This is the model class for table "yl_activity".
 *
 * @property string $id
 * @property string $member_name 用户姓名
 * @property string $member_mobile 手机号
 * @property string $activity_token 活动页token
 * @property string $price 累计收益
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_name', 'member_mobile', 'activity_token'], 'required'],
            [['price'], 'integer'],
            [['member_name'], 'string', 'max' => 50],
            [['member_mobile'], 'string', 'max' => 11],
            [['activity_token'], 'string', 'max' => 32],
            [['member_mobile'], 'unique'],
            [['activity_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_name' => 'Member Name',
            'member_mobile' => 'Member Mobile',
            'activity_token' => 'Activity Token',
            'price' => 'Price',
        ];
    }
}
