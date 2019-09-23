<?php

namespace cms\modules\member\models;

use Yii;
use yii\mongodb\Query;
/**
 * This is the model class for table "yl_order_throw_list".
 *
 * @property string $id
 * @property string $throw_id 和order_throw表ID关联
 * @property string $order_id 订单ID
 */
class ReportMongo extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['order_arrival_report'];
    }


    public function attributes() {
        return [
            '_id',
            'area_name',
            'arrival_rate',
            'shop_id ',
            'shop_name',
            'street_name',
            'order_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_name', 'arrival_rate', 'shop_id', 'shop_name', 'street_name', 'order_id',"software_number"], 'safe'],
        ];
    }


    public function getArrivalRateReportView($id,$table){
        $query = new Query();
        $data = $query->from($table)->where(['_id'=>$id])->one();
        return $data;
    }

    /**
     * 广告维护指派，获取到达率
     * @param $id
     * @param $table
     * @return int
     */
    public static function getArrivalRate($id,$table){
        $query = new Query();
        $data = $query->from($table)->where(['_id'=>$id])->one();
        if(!empty($data)){
            if(isset($data['arrival_rate']) && $data['arrival_rate']){
                return $data['arrival_rate'];
            }
            return 0;
        }
        return 0;
    }
}
