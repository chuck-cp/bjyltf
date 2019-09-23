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
class OrderThrowOrderDate extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }

    private static $partitionIndex_ = 1;

    private static function resetPartitionIndex($order_id = null) {
        self::$partitionIndex_ = ceil($order_id/2000);
    }

    public static function tableName()
    {
        return '{{%order_throw_order_date_'.self::$partitionIndex_.'}}';
    }

    /*
     * 获取订单的投放时间列表,并重组成以下格式
     * [
     *     '地区ID' => [
     *          [
     *              'start_at' => '2018-08-02',
     *              'end_at' => '2018-08-03',
     *          ],
     *          [
     *              'start_at' => '2018-08-05',
     *              'end_at' => '2018-08-05',
     *          ]
     *      ]
     * ]
     * */
    public static function getOrderThrowDateGroupArea($order_id){
        self::resetPartitionIndex($order_id);
        $dateModel = self::find()->where(['order_id'=>$order_id])->select('area_id,date')->asArray()->all();
        if(empty($dateModel)){
            return [];
        }
        $reformDate = [];
        foreach($dateModel as $date){
            $reformDate[$date['area_id']][] = strtotime($date['date']);
        }
        $result = [];
        foreach($reformDate as $area_id => $date){
            sort($date);
            $endDate = '';
            foreach($date as $key=>$value){
                //第一次计算
                if(empty($endDate)){
                    $result[$area_id][] = [
                        'start_at' => date('Y-m-d',$value),
                        'end_at' => date('Y-m-d',$value),
                    ];
                    $endDate = $value;
                }else{
                    if(strtotime('+1 day',$endDate) == $value){
                        //第二个日期和第一个相连
                        $result[$area_id][count($result[$area_id]) - 1]['end_at'] = date('Y-m-d',$value);
                        $endDate = $value;
                    }else{
                        //第二个日期和第一个不相连,创建一个新的日期区间
                        $result[$area_id][] = [
                            'start_at' => date('Y-m-d',$value),
                            'end_at' => date('Y-m-d',$value),
                        ];
                        $endDate = $value;
                    }
                }
            }
        }
        return $result;
    }

    public function findtrue($order_id){
        self::resetPartitionIndex($order_id);
        $data=self::find()->where(['order_id'=>$order_id])->select('area_id,date')->asArray()->all();
        foreach($data as $key=>$value){
            $newdate[$value['area_id']][$value['date']] =1;
        }
        return $newdate;
    }



}
