<?php

namespace console\models;
use common\libs\Redis;
use console\core\MongoActiveRecord;
use Yii;
use yii\console\Exception;
use common\libs\ToolsClass;
use yii\helpers\ArrayHelper;

/**
 * 店铺
 */
class Shop extends MongoActiveRecord
{
    //每月补贴的电费金额
    const monthElectricityFees = 600;
    //每日电费补贴金额
    const dayElectricityFees = 20;

    public static function tableName()
    {
        return '{{%shop}}';
    }

    /*
     * 加载统计数据
     * */
    protected function loadInstallNumber(&$memberInstallData,$shopList,$type){
        if($type == 1){
            $idFieldName = 'install_member_id';
            $shopFieldName = 'assign_shop_number';
            $screenFieldName = 'assign_screen_number';
            $addShopFieldName = 'screen_number';
        }elseif($type == 2){
            $idFieldName = 'install_member_id';
            $shopFieldName = 'assign_shop_number';
            $screenFieldName = 'assign_screen_number';
            $addShopFieldName = 'replace_screen_number';
        }elseif($type == 3){
            $idFieldName = 'member_id';
            $shopFieldName = 'install_shop_number';
            $screenFieldName = 'install_screen_number';
            $addShopFieldName = 'screen_number';
        }
        foreach($shopList as $shop){
            if(!isset($memberInstallData[$shop[$idFieldName]])){
                $memberInstallData[$shop[$idFieldName]] = [
                    'assign_shop_number'=>0,
                    'assign_screen_number'=>0,
                    'install_shop_number'=>0,
                    'install_screen_number'=>0,
                    'income_price'=>0
                ];
            }
            if($type == 3) {
                // 统计每日安装数量时，计算安装费用
                $memberInstallData[$shop[$idFieldName]]['income_price']  += $shop['install_price'];
            }
            $memberInstallData[$shop[$idFieldName]][$shopFieldName] += 1;
            $memberInstallData[$shop[$idFieldName]][$screenFieldName] += $shop[$addShopFieldName];
        }
    }

    /*
     * 统计每日安装数量
     * */
    public function countInstallNumber(){
        $yesTerDay = date('Y-m-d',strtotime('-1 day'));
        $memberInstallData = [];
        //查询所有当日指派的店铺
        $assignShopList = Shop::find()->select('install_member_id,screen_number')->where(['install_assign_at'=>$yesTerDay,'install_team_id'=>0])->asArray()->all();
        $this->loadInstallNumber($memberInstallData,$assignShopList,1);
        //查询更新屏幕所有当日指派的店铺
        $assignShopList = ShopScreenReplace::find()->select('install_member_id,replace_screen_number')->where(['assign_at'=>$yesTerDay])->asArray()->all();
        $this->loadInstallNumber($memberInstallData,$assignShopList,2);
        //统计每日安装的数量
        $installHistory = MemberInstallHistory::find()->where(['create_at'=>$yesTerDay])->select('shop_id,replace_id,type,screen_number,member_id')->asArray()->all();
        $installHistoryData = [];
        if(!empty($installHistory)) {
            foreach ($installHistory as $history){
                $reformInstallHistory[$history['type']][] = $history;
            }
            foreach ($reformInstallHistory as $key => $value){
                if ($key == 1) {
                    $shop_id = array_column($value,'shop_id');
                    $keyName = 'shop_id';
                    $installPriceData = Shop::find()->where(['id'=>$shop_id])->select('id,install_price')->indexBy('id')->asArray()->all();
                } else {
                    $keyName = 'replace_id';
                    $replace_id = array_column($value,'replace_id');
                    $installPriceData = ShopScreenReplace::find()->where(['id'=>$replace_id])->select('id,install_price')->indexBy('id')->asArray()->all();
                }
                foreach ($value as $historyKey => $historyValue) {
                    $installHistoryData[] = [
                        'screen_number' => $historyValue['screen_number'],
                        'member_id' => $historyValue['member_id'],
                        'install_price' => isset($installPriceData[$historyValue[$keyName]]) ? $installPriceData[$historyValue[$keyName]]['install_price'] : 0
                    ];
                }
            }
        }
        $this->loadInstallNumber($memberInstallData,$installHistoryData,3);

        //写入数据
        $dbTrans = \Yii::$app->db->beginTransaction();
        try{
            $installSubsidyModel = new MemberInstallSubsidy();
            foreach($memberInstallData as $key=>$value){
                $subsidyModel = clone $installSubsidyModel;
                $subsidyModel->install_member_id = $key;
                $subsidyModel->install_shop_number = isset($value['install_shop_number']) ? $value['install_shop_number'] : 0;
                $subsidyModel->install_screen_number = isset($value['install_screen_number']) ? $value['install_screen_number'] : 0;
                $subsidyModel->assign_shop_number = isset($value['assign_shop_number']) ? $value['assign_shop_number'] : 0;
                $subsidyModel->assign_screen_number = isset($value['assign_screen_number']) ? $value['assign_screen_number'] : 0;
                $subsidyModel->income_price = isset($value['income_price']) ? $value['income_price'] : 0;
                $subsidyModel->create_at = $yesTerDay;
                $subsidyModel->save();
            }
            $dbTrans->commit();
            ToolsClass::printLog("count_install_number","SUCCESS");
            return true;
        }catch (Exception $e){
            $message = '[count_install_number]'.$e->getMessage();
            ToolsClass::printLog("count_install_number",$message);
            \Yii::error($message);
            $dbTrans->rollBack();
            return false;
        }
    }

    /*
     * 加载统计数据
     * */
    public function loadApplyNumber(&$memberInstallData,$shopList,$type){
        if($type == 1){
            $shopFieldName = 'shop_number';
            $screenFieldName = 'screen_number';
        }elseif($type == 2){
            $shopFieldName = 'wait_install_shop_number';
            $screenFieldName = 'wait_install_screen_number';
        }
        if(!empty($shopList)){
            foreach($shopList as $shop){
                if(!isset($memberInstallData[$shop['member_id']])){
                    $memberInstallData[$shop['member_id']] = [
                        'shop_number'=>0,
                        'screen_number'=>0,
                        'wait_install_shop_number'=>0,
                        'wait_install_screen_number'=>0,
                    ];
                }
                $memberInstallData[$shop['member_id']][$shopFieldName] += 1;
                $memberInstallData[$shop['member_id']][$screenFieldName] += $shop['screen_number'];
            }
        }
    }

    /*
     * 统计每日联系的店铺数量
     * */
    public function countApplyNumber(){
        $memberInstallData = [];
        $yesTerDay = date('Y-m-d',strtotime('-1 day'));
        //查询已安装的数量
        $shopList = Shop::find()->where(['and',['>=','install_finish_at',$yesTerDay.' 00:00:00'],['<=','install_finish_at',$yesTerDay.' 23:59:59'],['status'=>5]])->select('member_id,screen_number')->asArray()->all();
        $this->loadApplyNumber($memberInstallData,$shopList,1);
        //查询所有待安装的数量
        $shopList = Shop::find()->where(['status'=>2])->groupBy('member_id')->select('member_id,screen_number')->asArray()->all();
        $this->loadApplyNumber($memberInstallData,$shopList,2);
        //查询所有的内部人员,计算出当天业绩为零的人
        $memberModel = Member::find()->where(['inside'=>1,'status'=>1])->select('id')->asArray()->all();
        if(!empty($memberModel)){
            foreach($memberModel as $member){
                if(!isset($memberInstallData[$member['id']])){
                    $memberInstallData[$member['id']] = [
                        'wait_install_screen_number' => 0,
                        'wait_install_shop_number' => 0,
                        'shop_number' => 0,
                        'screen_number' => 0,
                    ];
                }
            }
        }

        //写入数据
        $dbTrans = \Yii::$app->db->beginTransaction();
        try{
            $applyCountModel = new MemberShopApplyCount();
            foreach($memberInstallData as $key=>$value){
                $sql = "insert into yl_member_shop_apply_rank (member_id,week_shop_number,week_screen_number,count_shop_number,count_screen_number,month_shop_number,month_screen_number,wait_install_shop_number) values ({$key},{$value['shop_number']},{$value['screen_number']},{$value['shop_number']},{$value['screen_number']},{$value['shop_number']},{$value['screen_number']},{$value['wait_install_shop_number']}) ON DUPLICATE KEY UPDATE week_shop_number = week_shop_number + {$value['shop_number']},week_screen_number = week_screen_number + {$value['screen_number']},count_shop_number = count_shop_number + {$value['shop_number']},count_screen_number = count_screen_number + {$value['screen_number']},month_shop_number = month_shop_number + {$value['shop_number']},month_screen_number = month_screen_number + {$value['screen_number']},wait_install_shop_number = {$value['wait_install_shop_number']},wait_install_screen_number = {$value['wait_install_screen_number']}";
                //ToolsClass::printLog("count_apply_number",$sql);
                \Yii::$app->db->createCommand($sql)->execute();
                $countModel = clone $applyCountModel;
                $countModel->member_id = $key;
                $countModel->shop_number = $value['shop_number'];
                $countModel->screen_number = $value['screen_number'];
                $countModel->create_at = $yesTerDay;
                $countModel->save();
            }

            //周一(上周的数量等于本周的数量、本周数量清零)
            if(date('w') == 1){
                $sql = "update yl_member_shop_apply_rank set last_week_shop_number = week_shop_number,last_week_screen_number = week_screen_number,week_screen_number = 0,week_shop_number = 0";
                //ToolsClass::printLog("count_apply_number",$sql);
                \Yii::$app->db->createCommand($sql)->execute();
            }
            //每月16号(上半个月的数量等于本月的数量)
            if(date('d') == 16){
                $sql = "update yl_member_shop_apply_rank set last_half_past_month_shop_number = month_shop_number,last_half_past_month_screen_number = month_screen_number";
                //ToolsClass::printLog("count_apply_number",$sql);
                \Yii::$app->db->createCommand($sql)->execute();
            }
            //每月1号(上个月的数量等于本月的数量、上半个月的数量等于本月数量减上半个月的数量、本月数量清零)
            if(date('d') == 1){
                $sql = "update yl_member_shop_apply_rank set last_month_shop_number = month_shop_number,last_month_screen_number = month_screen_number,last_half_past_month_shop_number = month_shop_number - last_half_past_month_shop_number,last_half_past_month_screen_number = month_screen_number - last_half_past_month_screen_number,month_shop_number = 0,month_screen_number = 0";
                //ToolsClass::printLog("count_apply_number",$sql);
                \Yii::$app->db->createCommand($sql)->execute();
            }
            $dbTrans->commit();
            ToolsClass::printLog("count_apply_number",'SUCCESS');
            return true;
        }catch (Exception $e){
            $message = '[count_apply_number]'.$e->getMessage();
            ToolsClass::printLog("count_apply_number",$message);
            \Yii::error($message);
            $dbTrans->rollBack();
            return false;
        }
    }

    /*
     * 发放每月维护费
     * */
    public function grantMonthSubsidy(){
        $subsidyModel = ScreenRunTimeShopSubsidy::find()->where(['status'=>1,'grant_status'=>0])->select('shop_name,apply_id,id,shop_id,price')->asArray()->all();
        if(empty($subsidyModel)){
            ToolsClass::printLog("month_subsidy","没有要发放的数据");
            return true;
        }
        $dbTrans = Yii::$app->db->beginTransaction();
        try {
            foreach($subsidyModel as $subsidy){
                if ($subsidy['price'] > 0 && $subsidy['apply_id'] > 0) {
                    //执行钱的累加
                    if (!MemberAccount::addMoney($subsidy['apply_id'],$subsidy['price'],0,"subsidy",$subsidy['shop_name'])) {
                        throw new Exception("[month_subsidy]补贴ID:{$subsidy['id']},加钱失败");
                    }
                    ScreenRunTimeShopSubsidy::updateAll(['grant_status'=>1],['id'=>$subsidy['id'],'grant_status'=>0]);
                }
            }
            $dbTrans->commit();
        } catch (Exception $e) {
            $dbTrans->rollBack();
            Yii::error($e->getMessage());
        }
    }

    /*
     * 计算每月维护费
     * */
    public function reduceMonthSubsidy(){
        $dbTrans = \Yii::$app->throw_db->beginTransaction();
        try {
            $last_month = date('Ym',strtotime('-1 month'));
            $monthModel = ScreenRunTimeByMonth::find()->select('shop_id,software_number,number')->where(['date'=>$last_month])->asArray()->all();
            $reducePrice = [];
            if($monthModel) {
                $month_day = date("t", strtotime('-1 month'));
                foreach($monthModel as $month){
                    if (!isset($reducePrice[$month['shop_id']])) {
                        $reducePrice[$month['shop_id']] = [];
                    }
                    //计算用户的开机时长,并根据开机时长计算电费
                    if($month_day - $month['number'] >= 5){
                        //不符合要求,每天给2毛钱
                        $reducePrice[$month['shop_id']][$month['software_number']] = self::dayElectricityFees * $month['number'];
                    }else{
                        $reducePrice[$month['shop_id']][$month['software_number']] = self::monthElectricityFees;
                    }
                }
            }
            $shopModel = Shop::find()->joinWith('apply',false)->where(['and',['yl_shop.status'=>5],['<','yl_shop.install_finish_at',date('Y-m').'-01 00:00:00']])->select('yl_shop.id,shop_member_id,name,area_name,apply_name,apply_mobile,screen_number,area')->asArray()->all();
            if($shopModel) {
                $shopSubsidyModel = new ScreenRunTimeShopSubsidy();
                //计算店铺的总费用
                foreach($shopModel as $key => $value){
                    if (!isset($reducePrice[$value['id']])) {
                        $reducePrice[$value['id']] = [];
                    }
                    $shop_reduce_price = 0;
                    // 查询当前店铺的屏幕
                    $screenModel = Screen::find()->where(['shop_id'=>$value['id']])->select('shop_id,software_number')->asArray()->all();
                    if($screenModel) {
                        foreach ($screenModel as $screen) {
                            if (!isset($reducePrice[$value['id']][$screen['software_number']])) {
                                $reduce_price = 0;
                                $show = 0;
                            } else {
                                $show = 1;
                                $shop_reduce_price += $reducePrice[$value['id']][$screen['software_number']];
                                $reduce_price = $reducePrice[$value['id']][$screen['software_number']];
                            }
                            $sql = "insert into yl_screen_run_time_by_month (shop_id,software_number,`number`,`date`,price,reduce_price,is_show) values ({$screen['shop_id']},'{$screen['software_number']}',0,{$last_month},".self::monthElectricityFees.",{$reduce_price},{$show}) ON DUPLICATE KEY UPDATE is_show = {$show},reduce_price = {$reduce_price},price = ".self::monthElectricityFees;
                            Yii::$app->db->createCommand($sql)->execute();
                        }
                    }

                    $subsidyModel = clone $shopSubsidyModel;
                    $subsidyModel->shop_id = $value['id'];
                    $subsidyModel->area_id = $value['area'];
                    $subsidyModel->shop_name = $value['name'];
                    $subsidyModel->area_name = $value['area_name'];
                    $subsidyModel->apply_id = $value['shop_member_id'];
                    $subsidyModel->apply_name = $value['apply_name'];
                    $subsidyModel->apply_mobile = $value['apply_mobile'];
                    $subsidyModel->screen_number = $value['screen_number'];
                    $subsidyModel->date = $last_month;
                    $subsidyModel->reduce_price = $shop_reduce_price;
                    $subsidyModel->price = $value['screen_number'] * self::monthElectricityFees;
                    $subsidyModel->save();
                }

            }
            $dbTrans->commit();
            ToolsClass::printLog("reduce_month_subsidy", 'SUCCESS');
            return true;
        } catch (Exception $e){
            $message = '[reduce_month_subsidy]'.$e->getMessage();
            ToolsClass::printLog("reduce_month_subsidy",$message);
            \Yii::error($message);
            $dbTrans->rollBack();
            return false;
        }
    }


    // 获取店铺下的设备所在的坐标,转换成国标后存到mongo中
    public function getCoordinateToMongo() {
        $shopData = Redis::getInstance(1)->lrange('list_json_get_coordinate_to_mongo',0,-1);
        if(empty($shopData)){
            return;
        }
        foreach ($shopData as $value) {
            echo "{$value}\n";
            $shop = json_decode($value,true);
            try {
                $this->updateShopCoordinate($shop['shop_id'],$shop['software_number']);
                // 删除队列
                Redis::getInstance(1)->lrem('list_json_get_coordinate_to_mongo',$value,0);
            } catch (\Exception $e) {
                ToolsClass::printLog('list_int_get_coordinate_to_mongo', $value . ' ' . $e->getMessage());
            }
        }
    }

    // 获取店铺下的设备所在的坐标,转换成国标后存到mongo中
    public function updateShopCoordinate($shop_id,$software_number) {
        try {
            $shopModel = Shop::find()->where(['id'=>$shop_id])->select('name,area_name,address,longitude,latitude')->asArray()->one();
            // 获取坐标
            $shopCoordinate = ToolsClass::getDeviceCoordinate($software_number);
            if (empty($shopCoordinate) || empty($shopCoordinate[$software_number])) {
//                if (empty($shopModel['latitude']) || empty($shopModel['longitude'])) {
//                    throw new Exception("店铺ID:{$shop_id} 获取坐标失败");
//                }
                $shopCoordinateGd = [
                  'x' => $shopModel['longitude'],
                  'y' => $shopModel['latitude'],
                ];
            } else {
                $shopCoordinate = $shopCoordinate[$software_number];
                // 坐标转换
                $shopCoordinateGd = ToolsClass::coordinateCover(2,$shopCoordinate['longitude'], $shopCoordinate['latitude']);
                if (empty($shopCoordinateGd)) {
                    throw new Exception("店铺ID:{$shop_id} 获取坐标失败");
                }
                Shop::updateAll(['longitude' => $shopCoordinateGd['x'], 'latitude' => $shopCoordinateGd['y'], 'bd_longitude' => $shopCoordinate['longitude'],'bd_latitude' => $shopCoordinate['latitude']],['id' => $shop_id]);
            }
            $this->mongoDelete('shop',[
                'id' => (string)$shop_id,
            ]);
            // 写入mongo
            $this->mongoInsert('shop',[
                'id' => (string)$shop_id,
                'name' => $shopModel['name'],
                'address' => $shopModel['area_name'].$shopModel['address'],
                'loc' => [
                    'type'=>'Point',
                    'coordinates'=>[floatval($shopCoordinateGd['x']),floatval($shopCoordinateGd['y'])]
                ]
            ]);
            return true;
        } catch (\Exception $e) {
            Redis::getInstance(1)->rpush('list_json_get_coordinate_to_mongo_failed',json_encode([
                'shop_id' => $shop_id,
                'software_number' => $software_number
            ]));
            ToolsClass::printLog('list_int_get_coordinate_to_mongo',$e->getMessage());
            return false;
        }
    }

    public function getMailDateShop(){
        try {
            $model=Shop::find();
            $date=date('Y.m.d');
            $urldate=date('Y-m-d');
            $label=['160','23','23,188','11,91','18,107','18,160','11','23,97','23,91','23,168','15,97','27,97','15','11,34','23,34','107','168,188','23,27','18','23,48','11,100','18,34','18,19','11,23', '15,34','7','17,23','19','231','145','23,82','19,95','145,187','19,57','118','19,27','25','11,19','11,25','11,57','19,34','11,259', '15,19','11,15','11,17','11,115','19,28','11,145','11,32','19,109','11,27','11,32','27,34','27','11,42'];
            $BjTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(10111).')' => 10111],['not like','name','测试']])->count();//北京安装完成量
            $BjTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(10111).')' => 10111],['not like','name','测试'],['not in','lable_id',$label]])->count();//北京安装完成量
            $ShTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(10131).')' => 10131],['not like','name','测试']])->count();//上海安装完成量
            $ShTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(10131).')' => 10131],['not like','name','测试'],['not in','lable_id',$label]])->count();//上海安装完成量
            $GzTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(1014401).')' => 1014401],['not like','name','测试']])->count();//广州安装完成量
            $GzTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(1014401).')' => 1014401],['not like','name','测试'],['not in','lable_id',$label]])->count();//广州安装完成量
            $SzTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(1014403).')' => 1014403],['not like','name','测试']])->count();//深圳安装完成量
            $SzTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(1014403).')' => 1014403],['not like','name','测试'],['not in','lable_id',$label]])->count();//深圳安装完

            $HzTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(1013301).')' => 1013301],['not like','name','测试']])->count();//杭州安装完成量
            $HzTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(  1013301).')' => 1013301],['not like','name','测试'],['not in','lable_id',$label]])->count();//杭州安装完

            $TjTotalInstall=$model->where(['and',['status'=>5],['left(yl_shop.area,'.strlen(1011201).')' => 1011201],['not like','name','测试']])->count();//杭州安装完成量
            $TjTotalInstalled=$model->where(['and',['status'=>[2,3,4]],['left(yl_shop.area,'.strlen(1011201).')' => 1011201],['not like','name','测试'],['not in','lable_id',$label]])->count();//杭州安装完
            //  die;
            $azsum=$BjTotalInstall+$ShTotalInstall+$GzTotalInstall+$SzTotalInstall+$HzTotalInstall+$TjTotalInstall;//安装的总数
            $dazsum=$BjTotalInstalled+$ShTotalInstalled+$GzTotalInstalled+$SzTotalInstalled+$HzTotalInstalled+$TjTotalInstalled;//待安装的总数
            $mailerArr['mailer1']['body'] = "
                <p>胡总：</p>
                <p style='text-indent:2em;line-height:25px;;'>您好，地图中的数据是截止到今天下午（ $date ）16:00的，全国总计安装完成数量为：$azsum 个理发店，待安装数量为 $dazsum 个理发店。</p>
                <p style=\"text-indent:2em\">其中北京地区   安装完成数量为：$BjTotalInstall ，待安装数量为： $BjTotalInstalled 。</p>
                <p style=\"text-indent:2em\"> 上海地区    安装完成数量为：$ShTotalInstall ,待安装数量为： $ShTotalInstalled 。</p>
                <p style=\"text-indent:2em\"> 广州地区    安装完成数量为：$GzTotalInstall ，待安装数量为：$GzTotalInstalled 。</p>
                <p style=\"text-indent:2em\"> 深圳地区    安装完成数量为： $SzTotalInstall ，待安装数量为：$SzTotalInstalled 。</p>
                <p style=\"text-indent:2em\"> 杭州地区    安装完成数量为： $HzTotalInstall ，待安装数量为：$HzTotalInstalled 。</p>
                <p style=\"text-indent:2em\"> 天津地区    安装完成数量为： $TjTotalInstall ，待安装数量为：$TjTotalInstalled 。</p>
                <p style=\"text-indent:2em\">
                <table style=\"text-align: center; border-collapse: collapse;\">
                    <tr>
                        <td style='border: solid #0c0c0c 1px; width: 75px;height: 30px;background-color: #f4b084;font-size: 16px;font-weight: bold'>
                            区域
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;background-color: #f4b084;font-size: 16px;font-weight: bold;'>
                            总计安装完成数量
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;background-color: #f4b084;font-size: 16px;font-weight: bold'>
                            总计待安装数量
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            全国
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $azsum
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $dazsum
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            北京
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $BjTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $BjTotalInstalled
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            上海
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $ShTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $ShTotalInstalled
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            广州
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $GzTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $GzTotalInstalled
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            深圳
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $SzTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $SzTotalInstalled
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            杭州
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $HzTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $HzTotalInstalled
                        </td>
                    </tr>
                    <tr>
                        <td style='border: solid #0c0c0c 1px;width: 75px;height: 30px;'>
                            天津
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $TjTotalInstall
                        </td>
                        <td style='border: solid #0c0c0c 1px; width: 200px;height: 30px;'>
                            $TjTotalInstalled
                        </td>
                    </tr>
                </table>
                </p>
                <P style=\"text-indent:2em\"><a href='//www.bjyltf.com/maps/index?date=$urldate'>查看地图详情</a></P>
                <p style=\"text-indent:2em\">请您查看，谢谢</p>
                <p style=\"text-indent:2em\">B2B产品田雪</p>
                ";
            $mailerArr['mailer2']['body']= "
               胡总，您好，截止到今天下午4:00的理发店分布数据地图已发到您的邮箱。全国总计安装完成数量：$azsum ，待安装数量：$dazsum 。其中北京地区安装完成数量：$BjTotalInstall ，待安装数量：$BjTotalInstalled ；上海地区安装完成数量：$ShTotalInstall ，待安装数量：$ShTotalInstalled ；广州地区安装完成数量：$GzTotalInstall ，待安装数量：$GzTotalInstalled ；深圳地区安装完成数量：$SzTotalInstall ，待安装数量：$SzTotalInstalled ；杭州地区安装完成数量：$HzTotalInstall ，待安装数量：$HzTotalInstalled ；天津地区安装完成数量：$TjTotalInstall ，待安装数量：$TjTotalInstalled 。请您查看，谢谢。B2B产品田雪
        ";
           // $mailerArr['sttTo'][]
            $mailerArr['mailer1']['setSubject']="玉龙传媒理发店分布地图".'('.$date.')';
            $mailerArr['mailer2']['setSubject']="玉龙传媒理发店分布短信".'('.$date.')';
            foreach (explode(',',Yii::$app->params['recever']) as $v){
                foreach ($mailerArr as $vMail){
                    $mailer = Yii::$app->mailer->compose();
                    $mailer->setFrom(['1340747350@qq.com'=>'玉龙传媒']);
                    $mailer->setSubject($vMail['setSubject']);
                    $mailer->setHtmlBody($vMail['body']);
                    $mailer->setTo($v);
                    $send1=$mailer->send();
                }
            }
        }catch (Exception $e){
            Yii::error($e->getMessage());
        }
    }

    public function getApply(){
        return $this->hasOne(ShopApply::className(),['id'=>'id']);
    }
}
