<?php

namespace cms\modules\authority\models;

use Yii;

/**
 * This is the model class for table "yl_auth_area".
 *
 * @property string $user_id 用户ID
 * @property string $area_id 地区ID(多个以逗号分割)
 */
class AuthArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_auth_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
//        return [
//            [['user_id'], 'required'],
//            [['user_id'], 'integer'],
//            [['area_id'], 'string'],
//            [['user_id'], 'unique'],
//        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'area_id' => 'Area ID',
        ];
    }
}
