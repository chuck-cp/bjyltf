<?php

namespace cms\modules\examine\models;

use Yii;

/**
 * This is the model class for table "yl_order_date".
 *
 * @property string $id
 * @property string $order_id 关联yl_order表的id
 * @property string $start_at 开始时间
 * @property string $end_at 结束时间
 * @property int $is_update 是否可以修改(每次修改+1,等于3时不可再修改)
 */
class OrderDate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_order_date';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'start_at', 'end_at'], 'required'],
            [['order_id', 'is_update'], 'integer'],
            [['start_at', 'end_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'is_update' => 'Is Update',
        ];
    }

    /**
     * 获取投放日期
     */
    public static function getDeliveryDate($id){
        if(!$id){
            return '';
        }else{
            $findOne=self::findOne(['order_id'=>$id]);
            return self::findOne(['order_id'=>$id]) == true ? $findOne['start_at'].'至'.$findOne['end_at']:'';
        }
    }
}
