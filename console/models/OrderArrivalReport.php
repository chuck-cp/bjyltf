<?php

namespace console\models;

use common\libs\Redis;
use common\libs\ToolsClass;
use Yii;
use yii\mongodb\ActiveRecord;


class OrderArrivalReport extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%order_arrival_report}}';
    }

    public function attributes()
    {
        return ["_id","buyed_number","buyed_software_number","now_number","arrival_number","throw_over","street_name","shop_name","area_name","order_id","shop_id","software_number","arrival_rate"];
    }

    // 给线下更新节目设备生产播放日志
    public function generateLog()
    {
        $logModel = self::find()->where(['and',['throw_over' => 0],['>','maintain_id',0]])->select(['order_id','software_number'])->asArray()->all();
        if (empty($logModel)) {
            ToolsClass::printLog('generate_log','没有线下更新的设备');
        }
        $date = date("Y-m-d",strtotime('-1 day'));
        $play_number = [];
        foreach ($logModel as $value) {
            if (!isset($play_number[$value['order_id']])) {
                $orderModel = Order::findOne($value['order_id']);
                if (empty($orderModel)) {
                    continue;
                }
                $pNumber = $orderModel->getPlayNumber();
                if (empty($pNumber)) {
                    continue;
                }

                $play_number[$value['order_id']] = $pNumber;
            }
            foreach ($value['software_number'] as $screen) {
                if (!$screen['date']) {
                    echo Redis::getInstance(1)->rpush('system_throw_count_list','{"order_id":"'.$value['order_id'].'","device_number":"'.$screen['number'].'","play_number":"'.$play_number[$value['order_id']].'","count":"'.$date.'"}').PHP_EOL;
                }
            }
        }
    }
}
