<?php

namespace cms\modules\member\models;

use cms\models\SystemAddress;
/**
 * This is the model class for table "{{%order_area}}".
 *
 * @property string $id
 * @property string $order_id 关联yl_order表的id
 * @property string $area 地区ID
 */
class OrderArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id',], 'required'],
            [['order_id',], 'integer'],
            [['order_id',], 'unique'],
            [['street_area', 'area_id'], 'string'],
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
            //'area' => 'Area',
            'area_id' => 'area_id',
            'street_area' => 'street_area',
        ];
    }
    /*
     * 获取投放地区
     */
    public static function getAdvertAreaes($aid){
        $areaObj = self::find()->where(['order_id'=>$aid]);
        if($areaObj){
            $areasString = $areaObj->select('area_id')->asArray()->one();
                if(strpos($areasString['area_id'],',')){
                    $areaes = [];
                    $arr = explode(',',$areasString['area_id']);
                    foreach ($arr as $v){
                        $areaes[] = SystemAddress::getAreaNameById($v);
                    }
                    return $areaes;
                }
                return ['0' => SystemAddress::getAreaNameById($areasString['area_id'])];
        }
        return [];
    }
    /*
     * 获取订单下具体的街道list
     */
    public static function getStreetsByOrderId($oid){
        if($oid){
            $obj = self::find()->where(['order_id'=>$oid]);
            if(!$obj){
                return [];
            }
            return $obj->select('street_area')->asArray()->one();
        }
        return [];
    }
}
