<?php

namespace console\models;

use api\modules\v1\core\ApiActiveRecord;
use cms\models\SystemAddress;
use common\libs\ToolsClass;
use console\models\SystemConfig;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * 广告剩余量统计
 */
class OrderThrowProgramCount extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    private static $partitionIndex_ = null; // 分表ID
    /**
     * 重置分区id
     * @param unknown $order_id
     */
    private static function resetPartitionIndex($order_id = null) {
        // $partitionCount = 2000;

        self::$partitionIndex_ = ceil($order_id/2000);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_throw_program_count_'.self::$partitionIndex_.'}}';
    }

    // 根据省统计播放数据
    public function countByProvince($order_id)
    {
        self::resetPartitionIndex($order_id);
        $resultProvince = [];
        $resultCity = [];
        $countModel = self::find()->where(['order_id'=>$order_id])->select('area_id,play_number')->asArray()->all();
        if (empty($countModel)) {
            return [$resultProvince,$resultCity];
        }
        foreach ($countModel as $key => $value) {
            $province_id = substr($value,0,5);
            $city_id = substr($value,0,7);
            $resultCity[$city_id] = 0;
            if (isset($result[$province_id])) {
                $resultProvince[$province_id] += $value['play_number'];
            } else {
                $resultProvince[$province_id] = $value['play_number'];
            }
        }
        return [$resultProvince,array_keys($resultCity)];
    }

    public function findtrue($order_id){
        self::resetPartitionIndex($order_id);
        $data=self::find()->where(['order_id'=>$order_id])->select('area_id,count_at,play_number')->asArray()->all();
        $newdate=array();
        foreach($data as $key=>$value){
            $newdate[$value['area_id']][$value['count_at']] =$value["play_number"];
        }
        return $newdate;
    }



}
