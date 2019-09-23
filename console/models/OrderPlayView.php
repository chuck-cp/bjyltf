<?php

namespace console\models;

use common\libs\Redis;
use common\libs\ToolsClass;
use MongoCommandCursor;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use stdClass;
use Yii;
use yii\db\ActiveRecord;
use yii\mongodb\rbac\MongoDbManager;


class OrderPlayView extends ActiveRecord
{
    public $total_day = 0; // 订单投放总天数
    public $total_throw_screen_number = 0; // 屏幕数量 * 天数
    public static function getDb()
    {
        return Yii::$app->get('throw_db');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_play_view}}';
    }

    public function mongoAggregate($pipeline)
    {
        $manager = new Manager(Yii::$app->mongodb->dsn);
        $command = new Command([
            'aggregate' => 'order_throw_history',
            'pipeline' => $pipeline,
            'cursor' => new stdClass,
        ]);
        return $manager->executeCommand('guanggao', $command);
    }

    /*
     * 生成投放报告
     * @param order_id int 订单ID
     * */
    public function generateThrowReport($order_id)
    {
        echo $order_id.PHP_EOL;
        $dbTrans = Yii::$app->throw_db->beginTransaction();
        try {
            $orderModel = Order::find()->select('screen_number,advert_time,number,order_code,salesman_name,custom_service_name,advert_name')->where(['id'=>$order_id])->asArray()->one();
            $orderDataModel = OrderDate::find()->select('start_at,end_at')->where(['order_id' => $order_id])->asArray()->one();
            $this->order_id = $order_id;
            $this->order_code = $orderModel['order_code'];
            $this->salesman_name = $orderModel['salesman_name'];
            $this->custom_service_name = $orderModel['custom_service_name'];
            $this->advert_name = $orderModel['advert_name'];
            $this->advert_rate = $orderModel['number'];
            $this->advert_time = $orderModel['advert_time'];
            $this->start_at = $orderDataModel['start_at'];
            $this->end_at = $orderDataModel['end_at'];
            $this->total_day = $this->getTotalDay();
            list($provinceData,$cityData,$areaData,$streetNumber) = $this->getThrowData();
            if (!$this->writeThrowArea($provinceData)){
                throw new \Exception("写入投放地区数据失败");
            }
            if (!$this->writeThrowDate()){
                throw new \Exception("写入投放数据按日期统计失败");
            }
            $this->throw_area = $this->getThrowArea($cityData);
            $this->throw_province_number = count($provinceData);
            $this->throw_city_number = count($cityData);
            $this->throw_area_number = count($areaData);
            $this->throw_street_number = $streetNumber;
            // 实际播放次数
            $this->total_play_number = array_sum($provinceData);
            // 播放总时长
            $this->total_play_time = $this->total_play_number * ToolsClass::minuteCoverSecond($this->advert_time);
            $this->setThrowNumber();
            // 订单应播次数
            $this->total_order_play_number = $this->getOrderPlayNumber($orderModel['screen_number']);
            // 播放率
            $this->total_play_rate = empty($this->total_play_number) ? 0 : ceil(($this->total_play_number / $this->total_order_play_number) * 100) . '%';
            // 直接观看人数
            $this->total_watch_number = $this->getWatchNumber($this->total_throw_screen_number);
            // 设备平均开机时长
            $this->screen_run_time = $this->getScreenRunTime($this->total_play_number,$this->total_throw_screen_number);
            // 不重复观看人数
            $this->total_no_repeat_watch_number = $this->getNoRepeatWatchNumber();
            // 每人次平均观看次数
            $this->total_people_watch_number = $this->getPeopleWatchNumberByOneTime();
            // 平均每人观看次数
            $this->people_watch_number = $this->getPeopleWatchNumber($this->total_play_number,$this->total_no_repeat_watch_number);;
            // 辐射人数
            $this->total_radiation_number = $this->getRadiationNumber($this->total_no_repeat_watch_number);
            // 到达率
            $this->total_arrival_rate = $this->getArrivalRate($orderModel['screen_number']);
            $this->save();
            $dbTrans->commit();
            echo 'SUCCESS'.PHP_EOL;
            return true;
        } catch (\Exception $e) {
            echo 'ERROR '.$e->getLine().' '.$e->getMessage().PHP_EOL;
            Yii::error($e->getMessage());
            $dbTrans->rollback();
            return false;
        }
    }


    // 获取投放数据
    public function getThrowData()
    {
        $throwData = $this->mongoAggregate([
            [
                '$match' => [
                    'order_id' => $this->order_id
                ],
            ],
            [
                '$group' => [
                    '_id' => '$area_id',
                    'throw_number' => [
                        '$sum' => '$throw_number'
                    ]
                ],
            ]
        ]);
        if (empty($throwData)) {
            return [0,0,0,0];
        }
        $resultProvince = [];
        $resultCity = [];
        $resultArea = [];
        $streetNumber = 0;
        foreach ($throwData as $key => $value) {
            $streetNumber += 1;
            $province_id = substr($value->_id,0,5);
            $area_id = substr($value->_id,0,9);
            $city_id = substr($value->_id,0,7);
            $resultCity[$city_id] = 0;
            $resultArea[$area_id] = 0;
            if (isset($resultProvince[$province_id])) {
                $resultProvince[$province_id] += $value->throw_number;
            } else {
                $resultProvince[$province_id] = $value->throw_number;
            }
        }
        return [$resultProvince,$resultCity,$resultArea,$streetNumber];
    }

    /*
     * 写入投放数据按日期统计
     * */
    public function writeThrowDate()
    {
        $throwData = $this->mongoAggregate([
            [
                '$match' => [
                    'order_id' => $this->order_id
                ]
            ],
            [
                '$group' => [
                    '_id' => '$date',
                    'throw_number' => [
                        '$sum' => '$throw_number'
                    ]
                ]
            ]
        ]);
        if (!$throwData) {
            return true;
        }
        $playDateModel = new OrderPlayViewDate();
        try {
            foreach ($throwData as $key => $value) {
                $cloneModel = clone $playDateModel;
                $cloneModel->order_id = $this->order_id;
                $cloneModel->date = $value->_id->toDateTime()->format('Y-m-d');
                $cloneModel->throw_number = $value->throw_number;
                $cloneModel->save();
            }
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    /*
     * 写入投放地区表数据
     * @param provinceData array 投放数据
     * */
    public function writeThrowArea($provinceData)
    {
        $addressModel = SystemAddress::find()->where(['id' => array_keys($provinceData)])->select('id,name')->asArray()->all();
        $playAreaModel = new OrderPlayViewArea();
        if(empty($addressModel)) {
            return true;
        }
        try {
            foreach ($addressModel as $key => $value) {
                $cloneModel = clone $playAreaModel;
                $cloneModel->area_name = $value['name'];
                $cloneModel->throw_number = $provinceData[$value['id']];
                $cloneModel->order_id = $this->order_id;
                $cloneModel->save();
            }
            return true;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
    }

    /*
     * 获取投放区域
     * @param cityData array 投放数据
     * */
    public function getThrowArea($cityData)
    {
        if (empty($cityData)) {
            return '';
        }
        $cityData = array_keys($cityData);
        $cityLength = count($cityData);
        if($cityLength > 10) {
            $cityData = array_slice($cityData,0,9);
        }
        $addressModel = SystemAddress::find()->where(['id' => $cityData])->select('name')->asArray()->all();
        if ($addressModel) {
            $addressModel = array_column($addressModel,'name');
            $throw_area = implode(",",$addressModel);
            if($cityLength > 10) {
                $throw_area .= '等'.$throw_area.'个地区';
            }
            return $throw_area;
        }
        return '';
    }

    // 设置投放的店铺、镜面、屏幕数量
    public function setThrowNumber()
    {
        $throwData = $this->mongoAggregate([
            [
                '$match' => [
                    'order_id' => $this->order_id,
                ]
            ],
            [
                '$group' => [
                    '_id' => '$software_number',
                    'throw_number' => [
                        '$sum' => '$throw_number',
                    ],
                    'software_number' => [
                        '$sum' => 1,
                    ],
                    'shop_id' => [
                        '$first' => '$shop_id',
                    ],
                    'unique_software_number' => [
                        '$first' => '$software_number',
                    ],
                    'is_give' => [
                        '$first' => '$is_give',
                    ]
                ],
            ],
        ]);
        if (empty($throwData)) {
            $this->give_shop_number = 0;
            $this->give_screen_number = 0;
            $this->give_play_number = 0;
            $this->give_watch_number = 0;
            $this->give_radiation_number = 0;
            $this->throw_shop_number = 0;
            $this->throw_screen_number = 0;
            $this->throw_mirror_number = 0;
        } else {
            $throwShop = [];
            $giveThrowShop = [];
            $giveThrowScreen = [];
            $giveThrowPlayNumber = 0;
            $throwScreenNumber = 0;
            foreach ($throwData as $key => $value) {
                $this->total_throw_screen_number += $value->software_number;
                $throwScreenNumber += 1;
                $throwShop[$value->shop_id] = 1;
                if ($value->is_give) {
                    $giveThrowShop[$value->shop_id] = 1;
                    $giveThrowScreen[$value->unique_software_number] = $value->software_number;
                    $giveThrowPlayNumber += $value->throw_number;
                }
            }
            if (empty($throwShop)) {
                $mirror_account = 0;
            } else {
                $mirrorNumber = Shop::find()->where(['id' => array_keys($throwShop)])->select('sum(mirror_account) as mirror_account')->asArray()->one();
                if (empty($mirrorNumber)) {
                    $mirror_account = 0;
                } else {
                    $mirror_account = $mirrorNumber['mirror_account'];
                }
            }
            $this->give_shop_number = count($giveThrowShop);
            $this->give_screen_number = count($giveThrowScreen);
            $this->give_play_number = $giveThrowPlayNumber;
            $this->give_watch_number = $this->getWatchNumber(array_sum($giveThrowScreen));
            $this->give_radiation_number = $this->getRadiationNumber($this->getNoRepeatWatchNumber(1));
            $this->throw_shop_number = count($throwShop);
            $this->throw_screen_number = $throwScreenNumber;
            $this->throw_mirror_number = $mirror_account;
        }
    }

    /*
     * 获取到达率
     * @param screen_number 应播屏幕数量
     * */
    public function getArrivalRate($screen_number)
    {
        $throwData = \Yii::$app->mongodb->getCollection('order_throw_history')->count([
            'order_id' => $this->order_id,
            'throw_number' => [
                '$gte' => (int)$this->advert_rate * 10
            ],
        ]);
        if (empty($throwData)) {
            $arriveRate = "0";
        } else {
            $arriveRate = ceil($throwData / $screen_number * 100);
            if ($arriveRate >= 85 and $arriveRate < 90){
                $arriveRate = "> 85";
            }else if ($arriveRate >= 90 and $arriveRate < 95){
                $arriveRate = "> 90";
            }else if ($arriveRate >= 95){
                $arriveRate = "> 95";
            }
        }
        return $arriveRate;
    }

    /*
     * 获取辐射人数
     * 广告总辐射人数=不重复观看人数 * 3
     * @param number int 不重复观看人数
     * */
    public function getRadiationNumber($number)
    {
        return $number * 3;
    }

    /*
     * 获取每人次平均观看次数
     * */
    public function getPeopleWatchNumberByOneTime()
    {
        if ($this->advert_rate <= 2) {
            return 1;
        } else {
            $number = $this->advert_rate / 2;
            if (floor($number) != $number) {
                $number = number_format($number,2);
            }
            return $number;
        }
    }

    /*
     * 获取每人平均观看次数
     * 平均观看次数 = 播放总次数 / 不重复观看人数
     * @param play_number int 播放总次数
     * @param number int 不重复观看人数
     * */
    public function getPeopleWatchNumber($play_number,$number)
    {
        if (empty($play_number)) {
            return 0;
        }
        $number = $play_number / $number;
        if (floor($number) != $number) {
            $number = number_format($number,2);
        }
        return $number;
    }

    // 计算订单投放总天数
    public function getTotalDay()
    {
        $start_at = strtotime($this->start_at);
        $end_at = strtotime($this->end_at);
        if ($end_at == $start_at) {
            return 1;
        }
        return (($end_at - $start_at) / 86400) + 1;
    }

    /*
     * 获取不重复观看人数
     * @param is_give int 是否是赠送数据统计
     * */
    public function getNoRepeatWatchNumber($is_give = 0)
    {
        if ($is_give) {
            $matchWhere = [
                'order_id' => $this->order_id,
                'is_give' => 1
            ];
        } else {
            $matchWhere = [
                'order_id' => $this->order_id,
            ];
        }
        $throwData = $this->mongoAggregate([
            [
                '$match' => $matchWhere,
            ],
            [
                '$group' => [
                    '_id' => '$date',
                    'software_number' => [
                        '$sum' => 1,
                    ],
                ],
            ]
        ]);
        if (empty($throwData)) {
            return 0;
        }
        $start_at = strtotime($this->start_at);
        $resultNumber = 0;
        foreach ($throwData as $key => $value) {
            $day = ((strtotime($value->_id->toDateTime()->format('Y-m-d')) - $start_at) / 86400)+1;
            if ($day <= 30) {
                $resultNumber += ceil($this->getWatchNumber($value->software_number) * 0.9);
            } elseif ($day <= 60) {
                $resultNumber += ceil($this->getWatchNumber($value->software_number) * 0.3);
            } else {
                $resultNumber += ceil($this->getWatchNumber($value->software_number) * 0.2);
            }
        }
        return $resultNumber;
    }

    /*
     * 获取直接观看人数
     * 每小时观看人数计算方法:每小时播放次数为1时每小时观看人数等于1,每小时播放次数大于1时每小时观看人数等于2
     * 直接观看人数 = 每小时观看人数 * 屏幕总数量
     * @param screen_number int 屏幕总数量
     * */
    public function getWatchNumber($screen_number)
    {
        if ($this->advert_rate == 1) {
            return $screen_number * 10;
        } else {
            return $screen_number * 20;
        }
    }

    /*
     * 获取应播次数
     * 应播次数 = 每小时播放的次数 * 屏幕总数量 * 10
     * @param screen_number int 屏幕总数量
     * */
    public function getOrderPlayNumber($screen_number)
    {
        return $this->advert_rate * $screen_number * 10;
    }

    /*
     * 获取设备平均开机时长
     * 设备平均开机时长 = 播放总次数 / 每小时播放的次数 / 屏幕数量
     * @param play_number int 播放总次数
     * @param screen_number int 屏幕总数量
     * */
    public function getScreenRunTime($play_number,$screen_number)
    {
        return empty($play_number) ? 0 : number_format($play_number / $this->advert_rate / $screen_number,2);
    }

    /*
     * 每日投放数据统计
     * */
    public function countData()
    {

    }
}