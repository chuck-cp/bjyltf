<?php

namespace console\controllers;

use cms\modules\ledmanage\ledmanage;
use common\libs\Redis;
use common\libs\ToolsClass;
use console\models\Screen;
use console\models\Shop;
use console\models\ShopApply;
use console\models\ShopScreenReplace;
use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Stomp\Client;
use Stomp\SimpleStomp;
use Stomp\Transport\Bytes;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\mssql\PDO;
use Yii;


class ScreenController extends Controller
{
    /*
     * 获取屏幕在线时长
     * */
    public function actionOnlineTime(){
        ToolsClass::printLog("online_time","开启运行");
        ini_set('default_socket_timeout', -1);  //不超时
        ini_set('memory_limit', '512M');
        $todayDate = date('Y-m-d', strtotime('-1 day'));
        $resultCurl = ToolsClass::curl(Yii::$app->params['pushProgram']."/front/device/selectDurationSumByDeviceId/".$todayDate,"",
            [
                'Authorization:',
                'Accept:application/json',
                'Content-Type:application/json;charset=utf-8'
            ],false);

        $resultCurl = json_decode($resultCurl,true);
        if(!isset($resultCurl['data']) || empty($resultCurl['data'])){
            ToolsClass::printLog("online_time","没有获取到数据");
            return;
        }
        $redis = Yii::$app->redis;
        $redis->select(4);
        foreach($resultCurl['data'] as $key => $value){
            try{
                $redis->rpush('system_screen_run_time_list',json_encode([
                    'software_number'=> $value['deviceNum'],
                    'date' => $todayDate,
                    'time' => $value['duration']
                ]));
            } catch (Exception $e) {
                ToolsClass::printLog("online_time","写入redis失败");
                Yii::error("[get_screen_run_time]写入redis失败".date('Y-m-d H:i:s').$e->getMessage());
            }
        }
    }

    public function actionUpredisScreen()
    {
        ToolsClass::printLog("system_equipment_area","开始");
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $redisObj->del('system_equipment_area');
        $screenid = Screen::find()->select('id,shop_id,software_number,number')->asArray()->all();
        foreach ($screenid as $key=>$value){
            $shop = Shop::findOne(['id'=>$value['shop_id']]);
            $newvalue = ['system_equipment_area'];
            $newvalue[]=$value['software_number'];
            $newvalue[]=$shop->area.",".$shop->id;
            $redisObj->executeCommand('hmset',$newvalue);
        }
        ToolsClass::printLog("system_equipment_area","结束");
    }

    //更新换屏表的数据，屏幕数
    public function actionUpReplaceScreen()
    {
        ToolsClass::printLog("yl_shop_screen_replace","开始");
        $replace = ShopScreenReplace::find()->where(['maintain_type'=>[2,3,4],'status'=>4])->asArray()->all();
        foreach ($replace as $kr=>$vr){
            if($vr['maintain_type']==2){
                $num = count(explode(',',$vr['install_device_number']));
                $res = ShopScreenReplace::updateAll(['replace_screen_number'=>$num],['id'=>$vr['id']]);
            }elseif($vr['maintain_type']==3){
                $num = count(explode(',',$vr['remove_device_number']));
                $res = ShopScreenReplace::updateAll(['replace_screen_number'=>$num],['id'=>$vr['id']]);
            }elseif($vr['maintain_type']==4){
                $num = count(explode(',',$vr['install_device_number']));
                $res = ShopScreenReplace::updateAll(['replace_screen_number'=>$num],['id'=>$vr['id']]);
            }
            var_dump($res.$vr['id']);
        }
        ToolsClass::printLog("yl_shop_screen_replace","结束");
    }

    //更新换屏表的数据，法人相关信息
    public function actionUpReplaceApply(){
        $shopR = ShopScreenReplace::find()->select('id,shop_id')->groupBy('shop_id')->asArray()->all();
        foreach ($shopR as $key=>$value){
            $shop = Shop::findOne(['id'=>$value['shop_id']]);
            $shopA = ShopApply::findOne(['id'=>$value['shop_id']]);
            if(!empty($shop) && !empty($shopA)){
                ShopScreenReplace::updateAll(['shop_member_id'=>$shop->shop_member_id,'apply_name'=>$shopA->apply_name,'apply_mobile'=>$shopA->apply_mobile],['shop_id'=>$value['shop_id']]);
            }
        }
    }
}
