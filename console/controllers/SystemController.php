<?php

namespace console\controllers;


use cms\modules\examine\models\ShopHeadquarters;
use common\libs\Redis;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use console\core\MongoActiveRecord;
use console\models\AdvertPosition;
use console\models\Order;
use console\models\OrderArea;
use console\models\OrderDate;
use console\models\Screen;
use console\models\ScreenRunTime;
use console\models\ScreenRunTimeByMonth;
use console\models\ScreenRunTimeShopSubsidy;
use console\models\Shop;
use console\models\ShopAdvertImage;
use console\models\SystemAddress;
use function PHPSTORM_META\map;
use yii\base\Controller;
use yii\mongodb\Exception;
use yii\mongodb\Query;

class SystemController extends Controller
{

    //批量推送
    public function actionAllshopadvert(){
        echo 'start'."</br>";
        //店铺
        $shopAd = ShopAdvertImage::find()->where(['shop_type'=>1])->groupBy('shop_id')->asArray()->all();
        foreach($shopAd as $ka=>$va){
            $push_shop_list['shop_id']=$va['shop_id'];
            $push_shop_list['head_id']=0;
            RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);
        }
        //总部
        $shopAd = ShopAdvertImage::find()->where(['shop_type'=>2])->groupBy('shop_id')->asArray()->all();
        foreach($shopAd as $ka=>$va){
            $push_shop_list['shop_id']=0;
            $push_shop_list['head_id']=$va['shop_id'];
            RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);
        }
        echo 'end';
    }

    // 重新生成用户协议
    public  function actionGenerateMemberAgreement()
    {
        $shopModel = Shop::find()->where(['status'=>5])->select('agreement_name')->asArray()->all();
        if(empty($shopModel))
        {
            $shopModel = [];
        }
        $headModel = ShopHeadquarters::find()->select('agreement_name')->where(['examine_status'=>1])->asArray()->all();
        if(empty($headModel))
        {
            $headModel = [];
        }
        $shopModel = array_merge($headModel,$shopModel);
        $redis = RedisClass::init(4);
        foreach ($shopModel as $shop)
        {
            $redis->rpush('system_member_agreement_list',json_encode(['agreement_name'=>$shop['agreement_name']]));
        }
    }

    // 恢复每个店铺每月开机数量的数据
    public function actionCountScreenRunTime()
    {
        ScreenRunTimeByMonth::deleteAll();
        $timeModel = ScreenRunTime::find()->asArray()->all();
        if (empty($timeModel)) {
            return;
        }
        foreach ($timeModel as $time) {
            if ($time['time'] < 3600 * 6){
                continue;
            }
            $sql = "insert into yl_screen_run_time_by_month (`date`,shop_id,software_number,`number`) values (".(int)date('Ym',strtotime($time['date'])).",{$time['shop_id']},'{$time['software_number']}',1) ON DUPLICATE KEY UPDATE number = number + 1";
            \Yii::$app->db->createCommand($sql)->execute();
        }
    }

    // 写入Mongo店铺
    public function actionShopToMongo()
    {
        $mongo = new MongoActiveRecord();
        $mongo->mongoDelete('shop',[]);
        $shopModel = new Shop();
        $shopData = Shop::find()->select('id')->where(['status'=>5])->asArray()->all();
        if (empty($shopData)){
            return;
        }
        foreach ($shopData as $shop) {
            $screenModel = Screen::find()->where(['shop_id'=>$shop['id']])->limit(1)->asArray()->one();
            if (empty($screenModel)) {
                ToolsClass::printLog('system_shop_to_mongo',"店铺ID:{$shop['id']} 没有找到设备");
                continue;
            }
            $shopModel->updateShopCoordinate($shop['id'],$screenModel['software_number']);
        }
    }

    public function actionShopAdvertImage()
    {
        $images = ShopAdvertImage::find()->asArray()->all();
        foreach ($images as $value) {
            $data = [
                "shop_type" => $value['shop_type'],
                "id" => $value['id'],
                "shop_id" => $value['shop_id'],
                "image_url" => str_replace("https://i1.bjyltf.com","http://yulongchuanmei-1255626690.cossh.myqcloud.com",$value['image_url']),
                "image_sha" => $value['image_sha']
            ];
            \common\libs\Redis::getInstance(1)->rpush('shop_advert_sha_check',json_encode($data));
            echo $value['id'].PHP_EOL;
        }
    }

    public function actionAdvertToMongo()
    {
//        $a = \Yii::$app->mongodb->getCollection('advert')->aggregate([
//            [
//                '$geoNear' => [
//                    'near' => [116.28726563723, 39.831991819871],
//                    'distanceField' => 'loc',
//                    'maxDistance' => 500000,
//                    'spherical' => true,
//                    'num' => 9999999
//                ],
//            ],
//            [
//                '$match' => [
//                    'advert_time' => 60,
//                    'advert_key' => 1,
//                    'date' => [
//                        '$lte' => '2019-06-18',
//                        '$gte' => '2019-06-18'
//                    ],
//                    'space_time' => [
//                        '$gte' => 300300300300300300300300300300
//                    ]
//                ]
//            ],
//            [
//                '$group' => [
//                    '_id' => '$shop_id',
//                ]
//            ]
//        ]);


//        aggregate([
//            [
//                '$group' => [
//                    '_id' => '$area_id',
//                    'shop_id' => [
//                        '$sum' => 1,
//                    ],
//                    'shop_id1' => [
//                        '$first' => '$shop_id',
//                    ]
//                ],
//            ]
//        ]);
//        $result = (new Query())->select(['area_id','shop_id'])->where([
//            'date' => '2019-02-21',
//        ])->from('advert')->aggregate([
//            '$group' => [
//                '_id' => 'area_id',
//                'shop_id' => [
//                    '$sum' => 1
//                ]
//            ]
//        ]);
//
//        print_r($result);exit;
//
//
//        $shopModel = new Shop();
//        $s = $shopModel->mongoFindAll('advert',[
//            'date' => [
//                '$gte' => '2019-02-21',
//                '$lte' => '2019-02-22',
//            ],
//            'area_id' =>  [
//                '$gte' => 101110100000,
//                '$lte' => 101110199999,
//            ],
//            'advert_key' => 2,
//            'advert_time' => '20',
//            'space_rate' => [
//                '$gte' => 5
//            ]
//        ],[
//            'select' => ['date','shop_id'],
//        ]);
//        print_r($s);
//        exit;
        $advertModel = AdvertPosition::find()->select('key,time')->asArray()->all();
        $advertList = [];
        $reformAdvertKey = [
            'A1' => 1,
            'A2' => 2,
            'B' => 3,
            'C' => 4,
            'D' => 5
        ];
        $defaultSpaceTime = [
            '1' => "300,300,300,300,300,300,300,300,300",
            '2' => "60,60,60,60,60,60,60,60,60,60",
            '3' => "300,300,300,300,300,300,300,300,300",
            '4' => "360,360,360,360,360,360,360,360,360,360",
            '5' => "360,360,360,360,360,360,360,360,360,360"
        ];
        foreach ($advertModel as $key => $value) {
            $advertList[$reformAdvertKey[$value['key']]] = array_map(function($i){
                return ToolsClass::minuteCoverSecond($i);
            },explode(",",$value['time']));
        }
        $iNumber = 1;
        $shopModel = new Shop();
        $shopList = $shopModel::find()->where(['status'=>5])->select('id,longitude,latitude,area')->asArray()->all();
        for($i = 15; $i < 380; $i++) {
            $date = date('Y-m-d',strtotime("+$i day"));
            foreach ($advertList as $aKey => $advertTime) {
                foreach ($advertTime as $time) {
                    foreach ($shopList as $shop) {
                        try {
                            $shopModel->mongoInsert('advert_stock_list',[
                                'shop_id' => (int)$shop['id'],
                                'area_id' => $shop['area'],
                                'advert_key' => $aKey,
                                'advert_time' => (int)$time,
                                'space_time' => $defaultSpaceTime[$aKey],
                                'date' => $date,
                                'loc' => [
                                    'type'=>'Point',
                                    'coordinates'=>[(double)$shop['longitude'],(double)$shop['latitude']]
                                ],
                                'space_rate' => 10,
                            ]);
                            echo $iNumber.PHP_EOL;
                            $iNumber++;
                        }catch (Exception $e){
                            print_r($e->getMessage().PHP_EOL);
                        }

                    }
                }
            }
        }
    }


    public function actionOrderAgreement()
    {
        $order = Order::find()->asArray()->where(['id' => 99])->all();
        foreach ($order as $orderModel) {
            try {
                $orderDateModel = OrderDate::find()->where(['order_id' => $orderModel['id']])->asArray()->one();
                $orderAreaModel = OrderArea::find()->select('street_area')->where(['order_id' => $orderModel['id']])->asArray()->one();
                $addressModel = SystemAddress::find()->where(['id' => $orderAreaModel['street_area']])->select('name')->asArray()->all();
                \common\libs\Redis::getInstance(4)->rpush('system_member_agreement_list',json_encode([
                    'type' => 'order',
                    'agreement_name' => $orderModel['order_code'].str_replace(["-"," ",":"],"",$orderModel['create_at']).$orderModel['member_id'].'.pdf',
                    'order_code' => $orderModel['order_code'],
                    'date' => $orderDateModel['start_at'].'至'.$orderDateModel['end_at'],
                    'advert_name' => $orderModel['advert_name'],
                    'advert_time' => $orderModel['advert_time'],
                    'rate' => $orderModel['rate'],
                    'final_price' => ToolsClass::priceConvert($orderModel['final_price']),
                    'payment_type' => $orderModel['payment_type'],
                    'area' => implode(",",array_column($addressModel,'name'))
                ]));
            } catch (\Throwable $e) {
                print_r($e->getMessage() . PHP_EOL);
            }
        }
    }

    //yl_screen_run_time_shop_subsidy更新shop_member_id
    public function actionUpShopMemberId()
    {
        var_dump('start');
        $shops = ScreenRunTimeShopSubsidy::find()->select('id,shop_id')->groupBy('shop_id')->asArray()->all();
        foreach ($shops as $key=>$value){
            $memberid = Shop::findOne(['id'=>$value['shop_id']]);
            if(empty($memberid)){
                continue;
            }
            ScreenRunTimeShopSubsidy::updateAll(['apply_id'=>$memberid->shop_member_id],['shop_id'=>$value['shop_id']]);
            var_dump($value['shop_id']);
        }
        var_dump('end');
    }
//  每天定时更新当天时间为一年+15天，并恢复可购买广告时长
    public function actionUpTime(){
        $defaultSpaceTime = [
            '1' => "300,300,300,300,300,300,300,300,300",
            '2' => "60,60,60,60,60,60,60,60,60,60",
            '3' => "300,300,300,300,300,300,300,300,300",
            '4' => "360,360,360,360,360,360,360,360,360,360",
            '5' => "360,360,360,360,360,360,360,360,360,360"
        ];
        $time=date("Y-m-d");
        $query = new Query();
        $result = $query->where(["date"=>$time])->from("advert_stock_list")->all();
        $shopModel = new Shop();
        foreach($result as $value){
            $value['space_time']=$defaultSpaceTime[$value['advert_key']];
            $time2=date('Y-m-d',strtotime("$time +15 day"));
            $value['date']=date("Y-m-d",strtotime("$time2 +1 year"));
            $res=$query->from("advert_stock_list")->where(['_id'=>$value['_id']])->modify($value);
        }

    }
}