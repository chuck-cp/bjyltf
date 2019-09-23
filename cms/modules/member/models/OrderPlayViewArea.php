<?php

namespace cms\modules\member\models;

use Yii;

/**
 * This is the model class for table "yl_order_play_view_area".
 *
 * @property string $id
 * @property string $order_id 订单id
 * @property string $area_name 地区名称
 * @property string $throw_number 投放次数
 */
class OrderPlayViewArea extends \yii\db\ActiveRecord
{
    const LIMIT_NUMBER = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yl_order_play_view_area';
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
            [['order_id', 'area_name'], 'required'],
            [['order_id', 'throw_number'], 'integer'],
            [['area_name'], 'string', 'max' => 50],
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
            'area_name' => 'Area Name',
            'throw_number' => 'Throw Number',
        ];
    }

    public static function getArea($order_id){
        $rank = self::find()->where(['order_id'=>$order_id])->orderBy('throw_number DESC')->asArray()->limit(self::LIMIT_NUMBER)->all();
        $data =  self::find()->where(['order_id'=>$order_id])->orderBy('throw_number DESC')->asArray()->all();
        if(empty($data)){ return $data; }
        $total = array_sum(array_column($data,'throw_number'));
        $re = [];
        $four = 0;
        foreach ($data as $k => $v){
            if($k < 4){
                $re[$k]['area'] = $v['area_name'];
                $re[$k]['throw_number'] = $v['throw_number'];
                $re[$k]['rate'] = round($v['throw_number']/$total,4)*100;
                $four += $v['throw_number'];
            }
        }
        if(count($data) > 4){
            array_push($re,[
                'area' => '其他',
                'throw_number' => $total - $four,
                'rate' => round(($total - $four)/$total,4)*100,
            ]);
        }
        return compact('re','rank');
    }
}
