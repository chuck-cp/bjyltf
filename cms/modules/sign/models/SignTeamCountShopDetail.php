<?php

namespace cms\modules\sign\models;

use Yii;

/**
 * This is the model class for table "yl_sign_team_count_shop_detail".
 *
 * @property string $id
 * @property string $team_id 团队ID
 * @property string $mongo_id 业务签到表mongo_id
 * @property string $sign_id 对应的第一次签到的ID
 * @property string $shop_name
 * @property int $sign_number 签到次数
 * @property string $create_at 统计日期
 */
class SignTeamCountShopDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_sign_team_count_shop_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'mongo_id', 'sign_id', 'sign_number'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['shop_name'], 'string', 'max' => 30],
            [['create_at', 'team_id', 'mongo_id'], 'unique', 'targetAttribute' => ['create_at', 'team_id', 'mongo_id']],
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
            'mongo_id' => 'Mongo ID',
            'sign_id' => 'Sign ID',
            'shop_name' => 'Shop Name',
            'sign_number' => 'Sign Number',
            'create_at' => 'Create At',
        ];
    }

    public function getBusunsessArr(){
        return $this->hasMany(SignBusiness::className(),['mongo_id'=>'mongo_id']);
    }
}
