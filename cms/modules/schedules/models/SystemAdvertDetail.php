<?php

namespace cms\modules\schedules\models;

use Yii;

/**
 * This is the model class for table "yl_system_advert_detail".
 *
 * @property string $id
 * @property string $advert_position_key 广告位标识
 * @property string $create_at 日期
 * @property int $rate 已使用频次
 */
class SystemAdvertDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_system_advert_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advert_position_key', 'create_at'], 'required'],
            [['create_at'], 'safe'],
            [['rate'], 'integer'],
            [['advert_position_key'], 'string', 'max' => 5],
            [['create_at', 'advert_position_key'], 'unique', 'targetAttribute' => ['create_at', 'advert_position_key']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'advert_position_key' => 'Advert Position Key',
            'create_at' => 'Create At',
            'rate' => 'Rate',
        ];
    }
}
