<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-23
 * Time: 13:30
 */

namespace cms\models;

use Yii;

/**
 * This is the model class for table "{{%order_message}}".
 *
 * @property string $id 主键
 * @property int $order_id yl_order 的主键
 * @property int $type 类型 1 ： 付款状态  2：投放状态
 * @property string $desc 操作说明
 * @property string $reject_reason 驳回原因
 * @property string $create_at 添加时间
 */
class OrderThrowOrderDate extends \yii\db\ActiveRecord{
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
        return '{{%order_throw_order_date_'.self::$partitionIndex_.'}}';
    }

    public static function findtrue($order_id,$area,$datelist){
        //数据读取redis
        $redisobj = Yii::$app->redis;
        $redisobj->select(4);
        $total_day = count($datelist);
        if(!empty($datelist)){
            foreach($area as $key=>$value){
                foreach ($datelist as $kd=>$vd){
                    $newdate[$value][$vd] = $redisobj->getbit('order_buy_result:'.$order_id,($key * $total_day) + $kd + 1);
                }
            }
            return $newdate;
        }else{
            return array();
        }
        //数据读取数据库
//        self::resetPartitionIndex($order_id);
//        $data=self::find()->where(['and',['order_id'=>$order_id],['area_id'=>$area],['>=','date',$datelist['start_at']],['<=','date',$datelist['end_at']]])->asArray()->all();
//        if(!empty($data)){
//            foreach($data as $key=>$value){
//                $newdate[$value['area_id']][$value['date']] = 1;
//            }
//            return $newdate;
//        }else{
//            return array();
//        }
    }
}