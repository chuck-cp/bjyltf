<?php

namespace cms\modules\shop\models;

use Yii;

/**
 * This is the model class for table "yl_screen_run_time".
 *
 * @property string $id
 * @property string $date 日期
 * @property string $software_number 屏幕软件编码
 * @property string $shop_id 店铺ID
 * @property int $time 屏幕运行时长
 */
class ScreenRunTime extends \yii\db\ActiveRecord
{
    public $time_sum;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_screen_run_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'software_number', 'shop_id'], 'required'],
            [['date','time_sum'], 'safe'],
            [['shop_id', 'time'], 'integer'],
            [['software_number'], 'string', 'max' => 32],
            [['software_number', 'date'], 'unique', 'targetAttribute' => ['software_number', 'date']],
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
            'software_number' => '屏幕软件编码',
            'shop_id' => 'Shop ID',
            'time' => 'Time',
        ];
    }

    public function getShopName(){
        return $this->hasOne(self::className(),['shop_id'=>'shop_id'])->select('id,shop_name');
    }
}
