<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_order_play_view_date".
 *
 * @property string $id
 * @property string $order_id 订单id
 * @property string $date 日期
 * @property string $throw_number 投放次数
 */
class OrderPlayViewDate extends \yii\db\ActiveRecord
{
    const LIMIT_NUMBER = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_order_play_view_date';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'date'], 'required'],
            [['order_id', 'throw_number'], 'integer'],
            [['date'], 'safe'],
            [['order_id', 'date'], 'unique', 'targetAttribute' => ['order_id', 'date']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'date' => 'Date',
            'throw_number' => 'Throw Number',
        ];
    }

    public static function getRank($order_id){
        return self::find()->where(['order_id'=>$order_id])->orderBy(['throw_number'=>SORT_DESC,'date'=>SORT_DESC])->limit(self::LIMIT_NUMBER)->asArray()->all();
    }
}
