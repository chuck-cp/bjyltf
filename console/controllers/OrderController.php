<?php

namespace console\controllers;

use cms\models\MemberMessage;
use common\libs\Redis;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use console\models\Order;
use console\models\OrderArrivalReport;
use console\models\OrderDate;
use console\models\OrderPlayView;
use console\models\OrderThrowProgramCount;
use console\models\Shop;
use console\models\ShopHeadquartersList;
use console\models\SystemAdvert;
use console\models\SystemAdvertExamine;
use console\models\SystemTestShop;
use yii\console\Controller;

class OrderController extends Controller
{

    /*
     * 线下更新的设备生产播放量日志
     * */
    public function actionGenerateLog()
    {
        $orderModel = new OrderArrivalReport();
        $orderModel->generateLog();
    }

    /*
     * 生成到达率报告
     * */
    public function actionArrivalReport()
    {
        while (True) {
            try {
                $orderData = Redis::getInstance(5)->rpop('generate_arrival_report');
                if(empty($orderData)){
                    sleep(3);
                    continue;
                }
                \Yii::$app->db->open();
                \Yii::$app->throw_db->open();
                $orderModel = new Order();
                $orderModel->generateArrivalReport((int)$orderData);
                \Yii::$app->db->close();
                \Yii::$app->throw_db->close();
            } catch (\Exception $e) {
                print_r($e->getMessage());
                sleep(3);
            }
        }

    }

    /*
     * 订单每日投放数据统计
     * */
    public function actionCount()
    {
        $viewModel = new OrderPlayView();
        $viewModel->countData();
    }

    /*
     * 生成订单投放报告
     * */
    public function actionReport()
    {
        while (True) {
            try {
                $orderData = Redis::getInstance(1)->rpop('order_report_list');
                if(empty($orderData)){
                    sleep(3);
                    continue;
                }
                \Yii::$app->db->open();
                \Yii::$app->throw_db->open();
                $viewModel = new OrderPlayView();
                $viewModel->generateThrowReport((int)$orderData);
                \Yii::$app->db->close();
                \Yii::$app->throw_db->close();
            } catch (\Exception $e) {
                print_r($e->getMessage());
                sleep(3);
            }
        }
    }

    /*
     * 处理逾期订单
     */
    public function actionOverdue()
    {
        $orderModel = new Order();
        $orderModel->updateOverdueOrder();
    }

    /*
     * 处理投放中订单
     */
    public function actionDelivery()
    {
        $orderModel = new Order();
        $orderModel->updateDeliveryOrder();
    }


    /*
     * 处理投放完成订单
     */
    public function actionComplete()
    {
        $orderModel = new Order();
        $orderModel->updateCompleteOrder();
    }

    /*
     * 订单打点
     * */
    public function actionPoint() {
        while (True) {
            try {
                $orderData = Redis::getInstance(5)->lrange('order_point_list',0,-1);
                if(empty($orderData)){
                    sleep(3);
                    continue;
                }
                \Yii::$app->db->open();
                \Yii::$app->throw_db->open();
                $orderData = array_unique($orderData);
                $orderModel = new Order();
                foreach ($orderData as $order) {
                    $orderModel->orderPoint($order);
                }
                \Yii::$app->db->close();
                \Yii::$app->throw_db->close();
            } catch (\Exception $e) {
                print_r($e->getMessage());
                sleep(3);
            }
        }
    }

    /*
     * 订单排期
     * */
    public function actionScheduling() {
//        $orderModel = new Order();
//        \Yii::$app->throw_db->open();
//        foreach (['A1','A2','B','C','D'] as $advert_key) {
//            $orderModel->orderScheduling($advert_key);
//        }
//        exit;
        while (True) {
            try {
                $schedulingData = Redis::getInstance(5)->lrange('order_scheduling_list',0,-1);
                if(empty($schedulingData)){
                    sleep(3);
                    continue;
                }
                $orderModel = new Order();
                \Yii::$app->throw_db->open();
                foreach ($schedulingData as $advert_key) {
                    $orderModel->orderScheduling($advert_key);
                }
                \Yii::$app->throw_db->close();
            } catch (\Exception $e) {
                print_r($e->getMessage());
                sleep(3);
            }
        }
    }

    /*
     * 订单推送
     * */
    public function actionPush() {
//        $orderModel = new Order();
//        $orderModel->pushProgramList();
//        exit;
        while (True) {
            try {
                $pushData = Redis::getInstance(5)->lpop('order_push_list');
                if(empty($pushData)){
                    sleep(3);
                    continue;
                }
                \Yii::$app->throw_db->open();
                $orderModel = new Order();
                $orderModel->pushProgramList();
                \Yii::$app->throw_db->close();

            } catch (\Exception $e) {
                print_r($e->getMessage());
                sleep(3);
            }
        }
    }

    /*
     * 订单锁定
     */
    public function actionLock(){
        $orderModel = new Order();
        ToolsClass::printLog("push_program","锁定订单开始");
        $orderModel->updateLockOrder();
    }


    /*
     * 队列执行失败后恢复的脚本(1分钟执行一次)
     * */
    public function actionFailedList()
    {
        $failedList = ['system_advert_cell_out_area_list_failed','system_create_shop_list_failed','system_mysql_list_rate_space_2_failed','system_mysql_list_rate_space_failed',array('db'=>1,'key'=>'system_push_data_to_device_failed_list'),array('db'=>1,'key'=>'list_json_device_bind_failed'),array('db'=>1,'key'=>'list_get_shop_coordinate_failed'),array('db'=>1,'key'=>'list_json_sign_coordinate_convert_failed'),array('db'=>1,'key'=>'list_json_get_coordinate_to_mongo_failed'),array('db'=>1,'key'=>'system_throw_count_list_failed')];
        $fieldList = ['system_advert_cell_out_area_list','system_create_shop_list','system_mysql_list_rate_space_2','system_mysql_list_rate_space',array('db'=>1,'key'=>'system_push_data_to_device_list'),array('db'=>1,'key'=>'list_json_device_bind'),array('db'=>1,'key'=>'list_get_shop_coordinate'),array('db'=>1,'key'=>'list_json_sign_coordinate_convert'),array('db'=>1,'key'=>'list_json_get_coordinate_to_mongo'),array('db'=>1,'key'=>'system_throw_count_list')];
        foreach ($failedList as $key => $value) {
            $db = 4;
            if (is_array($value)) {
                $db = $value['db'];
                $value = $value['key'];
            }
            $shopData = Redis::getInstance($db)->lrange($value,0,-1);
            if(empty($shopData)){
                echo $value.'没有数据'.PHP_EOL;
                continue;
            }
            $failedDb = 4;
            $failedKey = $fieldList[$key];
            if (is_array($failedKey)) {
                $failedDb = $fieldList[$key]['db'];
                $failedKey = $fieldList[$key]['key'];
            }
            $shopData = array_unique($shopData);
            foreach ($shopData as $shop) {
                try {
                    echo $failedKey.' '.$shop.PHP_EOL;
                    Redis::getInstance($failedDb)->rpush($failedKey, $shop);
                    Redis::getInstance($db)->lrem($value, $shop, 0);
                } catch (\Exception $e) {
                    ToolsClass::printLog($value, $shop . ' ' . $e->getMessage());
                }
            }
        }
    }


    /*
     * 将推送失败的店铺重新写入推送列表(10分钟执行一次)
     * */
    public function actionPushShopFailedList() {
        $failedList = ['push_shop_failed_list','order_scheduling_failed_list','order_point_failed_list'];
        $fieldList = ['push_shop_list','order_scheduling_list','order_point_list'];
        foreach ($failedList as $key => $value) {
            $shopData = Redis::getInstance(5)->lrange($value,0,-1);
            if(empty($shopData)){
                continue;
            }
            $shopData = array_unique($shopData);
            foreach ($shopData as $shop) {
                try {
                    Redis::getInstance(5)->rpush($fieldList[$key], $shop);
                    Redis::getInstance(5)->lrem($value, $shop, 0);
                } catch (\Exception $e) {
                    ToolsClass::printLog($value, $shop . ' ' . $e->getMessage());
                }
            }
        }
    }

    /*
     * 推送系统自定义广告
     * */
    public function actionPushSystemAdvert()
    {
        $examineModel = new SystemAdvertExamine();
        if (!$examineModel->examineSuccess()) {
            ToolsClass::printLog("push_system_advert","当天的节目没有审核通过");
            return;
        }
        $advertModel = new SystemAdvert();
        $advertModel->isPushSystemAdvert();
        $advertModel->writePushShopId();
    }

    /*
     * 推送用户购买的广告
     * */
    public function actionPushMemberAdvert() {
        $pushAreaData = Redis::getInstance(5)->lrange('push_area_list',0,-1);
        if(empty($pushAreaData)){
            return;
        }
        $pushAreaData = array_unique($pushAreaData);
        foreach ($pushAreaData as $shopAreaValue) {
            try {
                $shopModel = Shop::find()->where(['area' => $shopAreaValue,'status'=>5])->select('headquarters_id,id')->asArray()->all();
                if (empty($shopModel)) {
                    ToolsClass::printLog('push_area_list', "地区:{$shopAreaValue} 没有店铺");
                    continue;
                }
                foreach ($shopModel as $shop) {
                    Redis::getInstance(5)->rpush('push_shop_list',json_encode(['head_id'=>$shop['headquarters_id'],'shop_id'=>$shop['id'],'area_id'=>$shopAreaValue]));
                }
                Redis::getInstance(5)->lrem('push_area_list', $shopAreaValue, 0);
            } catch (\Exception $e) {
                ToolsClass::printLog('push_area_list', $shopAreaValue . ' ' . $e->getMessage());
            }
        }
    }

    /*
     * 推送店铺广告
     * */
    public function actionPushShopAdvert(){
        $pushShopData = Redis::getInstance(5)->lrange('push_shop_custom_advert_list',0,-1);
        if(empty($pushShopData)){
            return;
        }
        $pushShopData = array_unique($pushShopData);
        foreach ($pushShopData as $shopDataValue){
            try{
                $shopData = json_decode($shopDataValue,true);
                if ($shopData['head_id'] > 0) {
                    $shopList = ShopHeadquartersList::find()->select('shop_id,branch_shop_area_id')->where(['and',['headquarters_id' => $shopData['head_id']],['>','shop_id',0]])->asArray()->all();
                    if (empty($shopList)) {
                        continue;
                    }
                    foreach ($shopList as $shop) {
                        Redis::getInstance(5)->rpush('push_shop_list',json_encode(['head_id'=>$shopData['head_id'],'shop_id'=>$shop['shop_id'],'area_id'=>$shop['branch_shop_area_id']]));
                    }
                } else {
                    $shopModel = Shop::find()->where(['id'=>$shopData['shop_id']])->select('area')->asArray()->one();
                    if ($shopModel) {
                        $area_id = $shopModel['area'];
                    } else {
                        $area_id = 0;
                    }
                    Redis::getInstance(5)->rpush('push_shop_list',json_encode(['head_id'=>$shopData['head_id'],'shop_id'=>$shopData['shop_id'],'area_id'=>$area_id]));
                }
                Redis::getInstance(5)->lrem('push_shop_custom_advert_list',$shopDataValue,0);
            }catch (\Exception $e){
                ToolsClass::printLog('push_shop_advert',$shopDataValue.' '.$e->getMessage());
            }
        }
    }
}
