<?php

namespace cms\models;

use Yii;

/**
 * This is the model class for table "yl_screen_run_time_by_month".
 *
 * @property string $id
 * @property string $date 年月
 * @property string $shop_id 店铺ID
 * @property string $software_number 屏幕软件编码
 * @property int $number 开机的天数
 * @property string $price 维护费(分)
 */
class ScreenRunTimeByMonth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%screen_run_time_by_month}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    /*public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }*/

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'shop_id', 'number', 'price'], 'integer'],
            [['shop_id', 'software_number'], 'required'],
            [['software_number'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'shop_id' => 'Shop ID',
            'software_number' => 'Software Number',
            'number' => 'Number',
            'price' => 'Price',
        ];
    }
}
