<?php

namespace cms\modules\config\models;

use Yii;

/**
 * This is the model class for table "yl_system_zone_list".
 *
 * @property string $id 主键
 * @property string $price 区域价钱/补助价钱（分）
 * @property string $price_type 类型（1.商家费用，2.补助费用）
 * @property string $create_user_id 添加人id
 * @property string $update_at 更新时间
 */
class SystemZoneList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yl_system_zone_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'month_price', 'create_user_id'], 'integer'],
            [['update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'month_price' => 'Month Price',
            'create_user_id' => 'Create User ID',
            'update_at' => 'Update At',
        ];
    }

    /**
     * price_id获取price
     */
    public static function getPrice($price_id){
        if(!$price_id){
            return 0;
        }
        $model = self::findOne(['id'=>$price_id]);
        return $model == true ? $model : 0;
    }

    /**
     *修改价格
     */
    public static function modfiyPrice($model,$price,$mprice){
        $model->price = $price * 100;
        $model->month_price = $mprice * 100;
        $model->create_user_id = Yii::$app->user->identity->getId();
        return $model->save();
    }
}
