<?php

namespace console\controllers;
use common\libs\Redis;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use console\models\AdvertCellOutArea;
use console\models\MemberAreaCount;
use console\models\OrderThrowProgramSpace;
use console\models\RedisSpaceRate;
use console\models\Screen;
use console\models\Shop;
use console\models\SystemAddress;
use console\models\SystemAddressLevel;

class RedisController extends \yii\console\Controller
{

    //redis初始化主函数
    public function actionIndex1(){
        //按省市区街道分别统计屏幕数量
        $this->actionAreascreen();
        //修复redis地区剩余频次
        $this->actionUpdateParentRate();
        //省市区的街道数量写入redis(已废弃)
        //$this->actionArea();
        //初始化屏幕软编码对应的街道
        $this->actionScreenarea();
        //恢复redis数据广告位剩余次数
        $this->actionRecoveryspace();
        //恢复redis广告位剩余可买时长表
        $this->actionRedisadvertspace();
        //获取已售完的地区数量
        $this->actionCellOutStreet();
        //初始市级对应的广告费等级
        $this->actionArealevel();
    }

    //按省市区街道分别统计屏幕数量
    public function actionAreascreen(){

        set_time_limit(0);
        //街道
        $townScreen = Shop::find()->where(['and',['status'=>5],['>','screen_number',0]])->groupBy('area')->select('count(id) as total_shop, sum(screen_number) as total_screen, area,sum(mirror_account) as total_mirror')->asArray()->all();
        //区域
        $areaScreen = Shop::findBySql('select count(id) as total_shop, sum(screen_number) as total_screen, left(area,9) as area,sum(mirror_account) as total_mirror from yl_shop WHERE `status`=5 and screen_number > 0 GROUP BY left(area,9)')->asArray()->all();
        //城市
        $cityScreen = Shop::findBySql('select count(id) as total_shop, sum(screen_number) as total_screen, left(area,7) as area,sum(mirror_account) as total_mirror from yl_shop WHERE `status`=5 and screen_number > 0 GROUP BY left(area,7)')->asArray()->all();
        //省份
        $proScreen = Shop::findBySql('select count(id) as total_shop, sum(screen_number) as total_screen, left(area,5) as area,sum(mirror_account) as total_mirror from yl_shop WHERE `status`=5 and screen_number > 0 GROUP BY left(area,5)')->asArray()->all();
        $redis = \Yii::$app->redis;
        $redis->select(3);
        $a = $redis->keys('system_screen_number:*');
        foreach ($a as $vs){
            $redis->DEL($vs);//删除所有
        }
        $redisKey = 'system_screen_number:';
        if(!empty($townScreen)){//街道
            foreach ($townScreen as $v){
                $sysadd3 = SystemAddress::findOne(['id'=>$v['area']]);//街12位
                $sysadd2 = SystemAddress::findOne(['id'=>substr($v['area'],0,9)]);
                $sysadd1 = SystemAddress::findOne(['id'=>substr($v['area'],0,7)]);
                $levels = SystemAddressLevel::findOne(['area_id'=>substr($v['area'],0,9)]);//等级
                $redis->set($redisKey.$v['area'], json_encode(['area_name'=>$sysadd1->name.'-'.$sysadd2->name.'-'.$sysadd3->name,'area_level'=>$levels->level,'screen_number'=>$v['total_screen'],'shop_number'=>$v['total_shop'],'mirror_number'=>$v['total_mirror']]));
            }
        }
        if(!empty($areaScreen)){//区域
            foreach ($areaScreen as $v){
                $sysadd3 = SystemAddress::findOne(['id'=>$v['area']]);//区9位
                $sysadd2 = SystemAddress::findOne(['id'=>substr($v['area'],0,7)]);
                $sysadd1 = SystemAddress::findOne(['id'=>substr($v['area'],0,5)]);
                $levels = SystemAddressLevel::findOne(['area_id'=>$v['area']]);//等级
                $redis->set($redisKey.$v['area'], json_encode(['area_name'=>$sysadd1->name.'-'.$sysadd2->name.'-'.$sysadd3->name,'area_level'=>$levels->level,'screen_number'=>$v['total_screen'],'shop_number'=>$v['total_shop'],'mirror_number'=>$v['total_mirror']]));
            }
        }
        if(!empty($cityScreen)){//市
            foreach ($cityScreen as $v){
                $sysadd3 = SystemAddress::findOne(['id'=>$v['area']]);//市7位
                $sysadd2 = SystemAddress::findOne(['id'=>substr($v['area'],0,5)]);
                $sysadd1 = SystemAddress::findOne(['id'=>substr($v['area'],0,3)]);
                $levels = SystemAddressLevel::find(['area_id'=>$v['area']])->where(['like','area_id',$v['area'].'%', false])->asArray()->all();//等级
                $redis->set($redisKey.$v['area'], json_encode(['area_name'=>$sysadd1->name.'-'.$sysadd2->name.'-'.$sysadd3->name,'area_level'=>max(array_column($levels,'level')),'screen_number'=>$v['total_screen'],'shop_number'=>$v['total_shop'],'mirror_number'=>$v['total_mirror']]));
            }
        }
        if(!empty($proScreen)){//省份
            foreach ($proScreen as $v){
                $sysadd3 = SystemAddress::findOne(['id'=>$v['area']]);//省5位
                $sysadd2 = SystemAddress::findOne(['id'=>substr($v['area'],0,3)]);
                $levels = SystemAddressLevel::find(['area_id'=>$v['area']])->where(['like','area_id',$v['area'].'%', false])->asArray()->all();//等级
                $redis->set($redisKey.$v['area'], json_encode(['area_name'=>$sysadd2->name.'-'.$sysadd3->name,'area_level'=>max(array_column($levels,'level')),'screen_number'=>$v['total_screen'],'shop_number'=>$v['total_shop'],'mirror_number'=>$v['total_mirror']]));
            }
        }
    }

//修复redis地区剩余频次
    public function actionUpdateParentRate(){
        $redis = RedisClass::init(4);
        $keyList = $redis->keys('system_advert_space_rate*');
        if(empty($keyList)){
            return false;
        }
        $rateData = $redis->executeCommand('mget',$keyList);
        $updateData = [];
        foreach($rateData as $key=>$value){
            $rate_key = explode(":",$keyList[$key]);
            if(strlen($rate_key[2]) == 12){
                $updateData[$rate_key[0].':'.$rate_key[1].':'.substr($rate_key[2],0,5)][] = $value;
                $updateData[$rate_key[0].':'.$rate_key[1].':'.substr($rate_key[2],0,7)][] = $value;
                $updateData[$rate_key[0].':'.$rate_key[1].':'.substr($rate_key[2],0,9)][] = $value;
            }
        }
        foreach($updateData as $key=>$value){
            if(count($value) == 1){
                #$updateData[$key] = $value[0];
                $redis->set($key,$value[0]);
                echo $key.'_'.$value[0]."\n";
            }else{
                $resultRate = [];
                foreach($value as $rates){
                    $rates = explode(",",$rates);
                    foreach($rates as $k=>$v){
                        if(!isset($resultRate[$k]) || $v < $resultRate[$k]){
                            $resultRate[$k] = $v;
                        }
                    }
                }
                #$updateData[$key] = implode(",",$resultRate);
                $redis->set($key,implode(",",$resultRate));
                echo $key.'_'.implode(",",$resultRate)."\n";
            }
        }
//        $result = $redis->executeCommand('mget',$updateData);
//        print_r($result);
    }
    /*
     * 写入可购买的街道ID
     * */
    public function actionSystemAreaStreetId(){
        $streetId = SystemAddress::find()->where(['level'=>6,'is_buy'=>1])->select('id')->all();
        if(empty($streetId)){
            echo 'street id empty';
            return false;
        }
        set_time_limit(0);
        $redis = \Yii::$app->redis;
        $redis->select(3);
        $redis->del('system_street_id');
        foreach($streetId as $id){
            echo $redis->sadd('system_street_id',$id['id']).PHP_EOL;
        }
    }

    //初始化屏幕软编码对应的街道
    public function actionScreenarea(){
        set_time_limit(0);
        $newvalue = ['system_equipment_area'];
        //获取店铺数量
        $shopdata = Shop::find()->where(['status'=>5])->select('id,area')->orderBy('id asc')->asArray()->all();
        foreach($shopdata as $k=>$v){
            //获取店铺 屏幕
            $screendata=Screen::find()->where(['shop_id'=>$v['id']])->select('software_number')->asArray()->all();
            foreach($screendata as $screenk=>$screenv){
                $newvalue[]=$screenv['software_number'];
                $newvalue[]=$v['area'].",".$v['id'];
            }
        }
        $redisObj =  \Yii::$app->redis;
        $redisObj->select(3);
        $redisObj->executeCommand('hmset',$newvalue);
    }

    /*
     * 写入已售完的街道ID(bitmap)
     * */
    public function actionCellOutStreet(){
        $redis = RedisClass::init(4);
        $keyList = $redis->keys('advert_cell_status*');
        if(!empty($keyList)){
            foreach($keyList as $key){
                $redis->del($key);
            }
        }
        $streetList = AdvertCellOutArea::find()->where(['>=','date',date('Y-m-d')])->asArray()->all();
        foreach($streetList as $street){
            $position = ToolsClass::reduceBigMapKey($street['advert_key'],$street['advert_time'],$street['rate'],$street['date']);
            echo "advert_cell_status:{$street['area_id']}:{$position}";
            echo $redis->executeCommand("setbit",["advert_cell_status:{$street['area_id']}",$position,1]).PHP_EOL;
        }
    }

    /*
     * 写入redis已售完的地区
     * */

    public function actionCellOutArea()
    {
        $redis = RedisClass::init(4);
        $keyList = $redis->keys('system_advert_space_rate_*');
        if(!empty($keyList)){
            foreach($keyList as $key){
                $redis->del($key);
            }
        }
        $cellOutData = AdvertCellOutArea::find()->where(['>=','date',date('Y-m-d')])->asArray()->all();
        if (empty($cellOutData)) {
            return;
        }
        foreach ($cellOutData as $key => $value) {
            $date = str_replace("-","",$value['date']);
            $area_id = substr($value['area_id'],0,9);
            $key = "system_advert_space_rate_{$value['advert_key']}:{$date}:{$area_id}:{$value['advert_time']}";
            $rateData = Redis::getInstance(4)->get($key);
            $shopNumber = Shop::find()->where(['area' => $value['area_id'],'status' => 5])->select('sum(mirror_account) as mirror_number,sum(screen_number) as screen_number,count(*) as shop_number')->asArray()->one();
            if (empty($shopNumber)) {
                $shopNumber = [
                    'mirror_number' => 0,
                    'shop_number' => 0,
                    'screen_number' => 0
                ];
            }
            $rate = $value['rate'] - 1;
            if (empty($rateData)) {
                $rateData = [["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0],
                    ["shop_number" => 0,"screen_number" => 0,"mirror_number" => 0]];
                $rateData[$rate] = [
                    'screen_number' => $shopNumber['screen_number'],
                    'shop_number' => $shopNumber['shop_number'],
                    'mirror_number' => $shopNumber['mirror_number']
                ];
            } else {
                $rateData = json_decode($rateData,true);
                $rateData[$rate] = [
                    'screen_number' => $rateData[$rate]['screen_number'] += $shopNumber['screen_number'],
                    'shop_number' => $rateData[$rate]['shop_number'] += $shopNumber['shop_number'],
                    'mirror_number' => $rateData[$rate]['mirror_number'] += $shopNumber['mirror_number']
                ];
            }
            echo $key.PHP_EOL;
            Redis::getInstance(4)->set($key,json_encode($rateData));
        }
    }

    /*
    * 恢复redis数据广告位剩余次数
    * */
    public function actionRecoveryspace(){
        $redisspacerate = new RedisSpaceRate();
        $redisspaceratecount = $redisspacerate->find()->count();
        $page = ceil($redisspaceratecount / 1000)+1;
        for ($i = 1; $i < $page; $i++) {
            $this->RedisSpaceRateIn($i, 1000);
        }
    }

    public function RedisSpaceRateIn($offset,$limit){
        $redis=\Yii::$app->redis;
        $redis->select(4);
        $redisspacerate=new RedisSpaceRate();
        $redisdata=$redisspacerate->find()->offset($offset)->limit($limit)->select('key,value')->asArray()->all();
        if(!empty($redisdata)){
            foreach($redisdata as $k=>$v){
                $redis->rpush($v['key'],$v['value']);
            }
        }
    }

    /*
 * 恢复redis广告位剩余可买时长表
 * */
    public function actionRedisadvertspace(){
        $redisspacerate = new OrderThrowProgramSpace();
        $redisspaceratecount = $redisspacerate->find()->count();
        $page = ceil($redisspaceratecount / 1000)+1;
        for ($i = 1; $i < $page; $i++) {
            $this->Redisadvertspacetime($i, 1000);
        }

    }

    public function Redisadvertspacetime($offset,$limit){
        $redis=\Yii::$app->redis;
        $redis->select(4);
        $redisspacerate=new OrderThrowProgramSpace();
        $redisdata=$redisspacerate->find()->offset($offset)->limit($limit)->select('id,area_id,advert_key,date,space_time')->asArray()->all();
        if(!empty($redisdata)){
            foreach($redisdata as $k=>$v){
                $key="system_advert_space_time_".$v['advert_key'].":".str_replace('-', '', $v['date']).":".$v['area_id'];
                $redis->rpush($key,$v['space_time']);
            }
        }
    }

    //初始市级对应的广告费等级
    public function actionArealevel(){
        set_time_limit(0);
        $newvalue = ['system_config_by_advert_price'];
        //获取所有市级
        $areadata =SystemAddress::find()->where(['level'=>4])->select('id')->orderBy('id asc')->asArray()->all();
        foreach($areadata as $k=>$v){
            $newvalue[]=$v['id'];
            $level=SystemAddressLevel::find()->where(['and',['level'=>0],['left(area_id,7)'=>$v['id']]])->select('id,level')->asArray()->one();
           if(!empty($level)){
               $newvalue[]=3;
           }else{
               $levelData=SystemAddressLevel::find()->where(['left(area_id,7)'=>$v['id']])->select('id,level')->orderBy('level DESC')->asArray()->one();
               $newvalue[]=$levelData['level'];
           }
        }
        $redisObj =  \Yii::$app->redis;
        $redisObj->select(3);
        $redisObj->executeCommand('hmset',$newvalue);
    }


    /*
     * 定期清理过期的redis数据
     */
    public function  actionClean(){
        $todayDate = date('Ymd', strtotime('-1 day'));
        $redis=\Yii::$app->redis;
        $redis->select(4);
        $data=$redis->keys('*'.$todayDate.'*');
        foreach($data as $k=>$v){
            $redis->del($v);
        }
        $redis->select(2);
        $data=$redis->keys('message:*');
        foreach($data as $k=>$v){
            $redis->del($v);
        }
    }

    /**
     * 将现有的已安装完成的店铺写入redis，转换成百度地图麻点数据
     */
    public function actionShopToMaps(){
        ToolsClass::printLog("shop_to_maps","开始");
        $shops = Shop::find()->where(['and',['status'=>5],['<>','bd_longitude',''],['<>','bd_latitude','']])->asArray()->all();
        foreach ($shops as $key=>$value){
            $params['operate'] = 'create';
            $params['title'] = $value['name'];
            $params['address'] = $value['area_name'].$value['address'];
            $params['longitude'] = $value['bd_longitude'];
            $params['latitude'] = $value['bd_latitude'];
            $params['area_id'] = $value['area'];
            $params['screen_number'] = $value['screen_number'];
            $params['shop_id'] = $value['id'];
            $params['mirror_account'] = $value['mirror_account'];
            $redisObj =  \Yii::$app->redis;
            $redisObj->select(1);
            $redisObj->rpush('shop_data_to_baidu_list',json_encode($params));
        }
        ToolsClass::printLog("shop_to_maps","结束");
    }

}
