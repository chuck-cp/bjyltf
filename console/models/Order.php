<?php

namespace console\models;

use common\libs\ArrayClass;
use common\libs\Redis;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 屏幕管理
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /*
     * 计算订单应播次数
     * */
    public function getPlayNumber()
    {
        return $this->number * $this->screen_number * 10;
    }

    /*
     * 生成到达率报告
     * */
    public function generateArrivalReport($order_id)
    {
        try{
            $orderScreenNumber = Redis::getInstance(3)->SMEMBERS('order_device_list:'.$order_id);
            if (empty($orderScreenNumber)) {
                return;
            }
            $shopData = [];
            foreach ($orderScreenNumber as $value) {
                $screenModel = Screen::find()->select('shop_id')->where(['software_number' => $value])->asArray()->one();
                if (empty($screenModel)) {
                    continue;
                }
                $reportModel  = OrderArrivalReport::find()->where(['order_id' => $order_id, 'shop_id' => (int)$screenModel['shop_id']])->one();
                if (empty($reportModel)) {
                    if (!isset($shopData[$screenModel['shop_id']])) {
                        $shopData[$screenModel['shop_id']] = Shop::find()->where(['id' => $screenModel['shop_id']])->select('shop_city,shop_area,shop_street,name')->asArray()->one();
                    }
                    $rModel = new OrderArrivalReport();
                    $rModel->order_id = $order_id;
                    $rModel->throw_over = 0;
                    $rModel->area_name = $shopData[$screenModel['shop_id']]['shop_city'].'-'.$shopData[$screenModel['shop_id']]['shop_area'];
                    $rModel->street_name = $shopData[$screenModel['shop_id']]['shop_street'];
                    $rModel->shop_name = $shopData[$screenModel['shop_id']]['name'];
                    $rModel->shop_id = (int)$screenModel['shop_id'];
                    $rModel->arrival_rate = 0;
                    $rModel->buyed_number = 1;
                    $rModel->buyed_software_number = [$value];
                    $screenModel = Screen::find()->select('software_number')->where(['shop_id' => $screenModel['shop_id']])->asArray()->all();
                    $software_number = [];
                    foreach ($screenModel as $screen) {
                        $software_number[] = [
                            "number" => (string)$screen['software_number'],
                            "date" => ""
                        ];
                    }
                    $rModel->now_number = count($software_number);
                    $rModel->arrival_number = 0;
                    $rModel->software_number = $software_number;
                    $rModel->save();
                } else {
                    $reportModel->buyed_software_number = array_merge($reportModel->buyed_software_number,[$value]);
                    $reportModel->buyed_number++;
                    $reportModel->save();
                }
            }
        } catch (\Exception $e){
            Yii::error("[generate_arrival_report]".$e->getMessage());
        }
    }

    /*
     * 处理已逾期的订单
     * */
    public function updateOverdueOrder(){
        $dbTrans = Yii::$app->db->beginTransaction();
        try{
            Yii::error("[order_overdue]query".date('Y-m-d H:i:s',time()));
            $orderModel = self::find()->where(['and', ['payment_status' => 1], ['<=', 'overdue_at',date('Y-m-d')]])->select('id')->asArray()->all();
            if(empty($orderModel)){
                return false;
            }
            $order_id = array_column($orderModel,'id');
            self::updateAll(['payment_status'=>2],['id'=>$order_id]);
            foreach($order_id as $id){
                $orderMessage = new OrderMessage();
                $orderMessage->saveMessage($id,2);
                $orderMessage->save();
            }
            $dbTrans->commit();
        }catch (Exception $e){
            $dbTrans->rollBack();
            Yii::error("[order_overdue]".$e->getMessage());
        }
    }

    /*
     * 修改投放状态已完成
     */
    public function updateCompleteOrder(){
        $dbTrans = Yii::$app->db->beginTransaction();
        try{
            ToolsClass::printLog("order_complete","开始执行");
            $orderModel = self::find()->joinWith('date',false)->where(['and',['examine_status' => 4],['<','end_at',date('Y-m-d')]])->select('yl_order.id')->asArray()->all();
            if(empty($orderModel)){
                return false;
            }
            $order_id = array_column($orderModel,'id');
            self::updateAll(['examine_status'=>5],['id'=>$order_id]);
            foreach($order_id as $id){
                $orderMessage = new OrderMessage();
                $orderMessage->saveMessage($id,5);
                $orderMessage->save();
                Redis::getInstance(1)->rpush("order_report_list",$id);
                OrderArrivalReport::updateAll(['throw_over' => 1],['order_id' => $id]);
            }
            $dbTrans->commit();
            ToolsClass::printLog("order_complete","执行结束");
        }catch (Exception $e){
            print_r($e->getMessage());
            $dbTrans->rollBack();
            Yii::error("[order_complete]".$e->getMessage());
        }
    }

    /*
     * 处理投放中的订单
     * */
    public function updateDeliveryOrder(){
        Yii::error("[order_delivery]query".date('Y-m-d H:i:s',time()));
        $orderData = self::find()->where(['examine_status' => 3])->select('part_time_order,id,final_price,salesman_id,salesman_name,salesman_mobile,company_area_id')->asArray()->all();
        $dbTrans = Yii::$app->db->beginTransaction();
        try {
            foreach ($orderData as $key => $value) {
                $orderDateData = OrderDate::find()->where(['order_id' => $value['id']])->asArray()->one();
                if (empty($orderDateData)) {
                    Yii::error('[order_delivery]没有找到订单日期,订单ID:' . $value['id']);
                    continue;
                }
                if (strtotime(date("Y-m-d")) < strtotime($orderDateData['start_at'])) {
                    continue;
                }
                if (!$grantMoney = OrderBrokerage::computeBrokerage($value)) {
                    throw new Exception("[order_delivery]计算订单佣金失败");
                }
                foreach ($grantMoney as $k => $v) {
                    //执行钱的累加
                    if (!MemberAccount::addMoney($v['member_id'], $v['price'], $value['salesman_id'],$v['type'])) {
                        throw new Exception("[order_delivery]加钱失败");
                    }
                }
                Order::updateAll(['examine_status'=>4],['id'=>$value['id']]);
                $orderMessage = new OrderMessage();
                $orderMessage->saveMessage($value['id'],4);
                $orderMessage->save();
            }
            $dbTrans->commit();
        } catch (Exception $e) {
            $dbTrans->rollBack();
            Yii::error($e->getMessage());
        }
    }

    /*
     * 锁定订单
     * */
    public function updateLockOrder() {
        $dbTrans = Yii::$app->db->beginTransaction();
        try {
            //读取已付全款、素材已上传、未锁定、开使时间距今小于7天的订单
            $orderModel = self::find()->joinWith('date',false)->select('yl_order.id')->where(['and',['examine_status' => 3],['payment_status' => 3],['lock' => 0],['<=','start_at',date('Y-m-d',strtotime("+7 day"))]])->asArray()->all();
            if(empty($orderModel)){
                Redis::getInstance(5)->rpush('order_scheduling_list','A1','A2','B','C','D');
               # Redis::getInstance(5)->rpush("order_push_list","1");
                return false;
            }
            $order_id = array_column($orderModel,'id');
            Order::updateAll(['lock' => 1],['id' => $order_id]);
            foreach ($order_id as $id) {
                Redis::getInstance(5)->rpush('generate_arrival_report',$id);
                Redis::getInstance(5)->rpush('order_point_list',$id);
            }
            $dbTrans->commit();
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            print_r($e->getMessage());
            return false;
        }

    }

    /*
     * 订单打点
     * */
    public function orderPoint($order_id) {
        $dbTrans = Yii::$app->throw_db->beginTransaction();
        try {
            ToolsClass::printLog("order point","$order_id:开始打点");
            $orderModel = self::find()->where(['id'=>$order_id])->select('payment_at,resource_attribute,advert_key,number,advert_time,resource')->asArray()->one();
            $orderModel['advert_time'] = ToolsClass::minuteCoverSecond($orderModel['advert_time']);
            $throwDate = OrderThrowOrderDate::getOrderThrowDateGroupArea($order_id);
            $programModel = new OrderThrowProgram();
            foreach ($throwDate as $street_area_id => $orderDate) {
                foreach ($orderDate as $data) {
                    $programModel = OrderThrowProgram::find()->where(['area_id' => $street_area_id,'advert_key' => $orderModel['advert_key'],'date' => $data['start_at']])->select('id')->asArray()->one();
                    if (empty($programModel)) {
                        $sql = "insert into yl_order_throw_program (area_id,advert_key,`date`,end_date) values ('{$street_area_id}','{$orderModel['advert_key']}','{$data['start_at']}','{$data['end_at']}')";
                        #echo $sql."\r\n";
                        Yii::$app->throw_db->createCommand($sql)->execute();
                        $program_id = Yii::$app->throw_db->getLastInsertID();
                    } else {
                        $program_id = $programModel['id'];
                    }
                    $end_at = date('Y-m-d',strtotime('+1 day',strtotime($data['end_at'])));
                    if (!OrderThrowProgram::find()->where(['area_id' => $street_area_id,'advert_key' => $orderModel['advert_key'],'date' => $end_at])->count()) {
                        $sql = "insert into yl_order_throw_program (area_id,advert_key,`date`,end_date) values ('{$street_area_id}','{$orderModel['advert_key']}','{$end_at}','{$end_at}')";
                        #echo $sql."\r\n";
                        Yii::$app->throw_db->createCommand($sql)->execute();
                    }
                    $programListModel = new OrderThrowProgramList();
                    for ($i = 0;$i < $orderModel['number'];$i++) {
                        $cloneProgramList = clone $programListModel;
                        $cloneProgramList->program_id = $program_id;
                        $cloneProgramList->order_id = $order_id;
                        $cloneProgramList->advert_time = $orderModel['advert_time'];
                        $cloneProgramList->resource = $orderModel['resource'];
                        $cloneProgramList->resource_attribute = $orderModel['resource_attribute'];
                        $cloneProgramList->payment_at = $orderModel['payment_at'];
                        $cloneProgramList->save();
                    }
                }
            }
            ToolsClass::printLog("order point",'SUCCESS');
            Redis::getInstance(5)->lrem('order_point_list', $order_id);
            if (!Redis::getInstance(5)->lrange('order_point_list',0,-1)) {
                // 开始排期
                Redis::getInstance(5)->rpush('order_scheduling_list','A1','A2','B','C','D');
            }
            $dbTrans->commit();
        } catch (\Exception $e) {
            Redis::getInstance(5)->rpush('order_point_failed_list', $order_id);
            $dbTrans->rollBack();
            print_r($e->getMessage());
        }
    }

    /*
     * 计算节目播放的时间点
     * */
    public function orderScheduling($advert_key){
        ToolsClass::printLog("order scheduling","$advert_key:开始排期");
        $dbTrans = Yii::$app->throw_db->beginTransaction();
        try {
            $throwProgramData = OrderThrowProgram::find()->where(['date' => date('Y-m-d',strtotime('+1 day')),'advert_key'=>$advert_key])->select('id')->asArray()->all();
            if(!empty($throwProgramData)){
                foreach ($throwProgramData as $key => $value) {
                    $throwProgramListData = OrderThrowProgramList::find()->where(['program_id' => $value['id']])->select('id,order_id,advert_time')->orderBy('payment_at asc')->asArray()->all();
                    if (empty($throwProgramListData)) {
                        continue;
                    }
                    if (!$this->updatePlayTime($advert_key, $throwProgramListData)) {
                        throw new \Exception("排期失败");
                    }
                }
            }
            Redis::getInstance(5)->lrem('order_scheduling_list', $advert_key);
            if (!Redis::getInstance(5)->lrange('order_scheduling_list',0,-1)) {
                // 开始推送
                Redis::getInstance(5)->rpush('order_push_list', 1);
            }
            ToolsClass::printLog("order scheduling",'SUCCESS');
            $dbTrans->commit();
        }catch (Exception $e){
            Redis::getInstance(5)->rpush('order_scheduling_failed_list', $advert_key);
            Yii::error($e->getMessage());
            $dbTrans->rollBack();
        }
    }

    /*
     * 计算单一购买的广告
     * @param array advert_key 广告位
     * @param string date 要计算的日期
     * */
    public function reduceSimpleAdvert($advert_key,$date){
        try{
            $throwProgramData = OrderThrowProgram::find()->where(['date' => $date,'advert_key'=>$advert_key])->select('advert_key,id')->asArray()->all();
            if(!empty($throwProgramData)){
                foreach ($throwProgramData as $key => $value) {
                    $throwProgramListData = OrderThrowProgramList::find()->where(['program_id' => $value['id']])->select('id,order_id,advert_time')->orderBy('payment_at asc')->asArray()->all();
                    if(empty($throwProgramListData)){
                        continue;
                    }
                    if(!$this->updatePlayTime($value['advert_key'],$throwProgramListData)){
                        return false;
                    }
                }
            }
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage());
            return false;
        }
    }

    /*
     * 计算绑定购买的广告
     * @param array advert_keys 广告位
     * @param string date 要计算的日期
     * */
    public function reduceCompositeAdvert($advert_keys,$date){
        try{
            $throwProgramListData = [];
            foreach ($advert_keys as $advert_key) {
                $throwProgramData = OrderThrowProgram::find()->where(['date' => $date,'advert_key'=>$advert_key])->select('id')->asArray()->all();
                if(!empty($throwProgramData)){
                    foreach ($throwProgramData as $key => $value) {
                        $tDate = OrderThrowProgramList::find()->where(['program_id' => $value['id']])->select('id,order_id,advert_time')->orderBy('payment_at asc')->asArray()->all();
                        $throwProgramListData += $tDate;
                    }
                }
                if(!$this->updatePlayTime($advert_key,$throwProgramListData)){
                    return false;
                }
            }
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage());
            return false;
        }

    }

    /*
     * 将计算好的播放时间写入数据库
     * @param string advert_key 广告标识
     * @param string throwProgramListData 要计算的数据集合
     * */
    public function updatePlayTime($advert_key,$throwProgramListData){
        if(empty($throwProgramListData)){
            return true;
        }
        try{
            // 存储排期的结果
            $pointDate = [];
            // 每个频次对应的起始时间
            $advertPositionStartTime = ToolsClass::getStatusBayNum($advert_key,'advertPositionStartTime');
            // 每个频次对应的剩余时间
            $advertPositionSpaceTime = ToolsClass::getStatusBayNum($advert_key,'advertPositionSpaceTime');
            $defaultSpaceTime = $advertPositionSpaceTime[0];
            foreach($throwProgramListData as $listKey => $listValue){
                foreach($advertPositionSpaceTime as $tKey => $tValue){
                    if (!isset($pointDate[$tKey])) {
                        $pointDate[$tKey] = [];
                    }
                    if(in_array($listValue['order_id'],$pointDate[$tKey]) || $listValue['advert_time'] > $tValue){
                        // 如果当前频次已经排过此订单或当前频次剩余时长不够排此订单时跳过此次循环
                        continue;
                    }
                    $pointDate[$tKey][] = $listValue['order_id'];
                    $start_at = $advertPositionStartTime[$tKey] + ($defaultSpaceTime - $advertPositionSpaceTime[$tKey]);
                    $advertPositionSpaceTime[$tKey] -= $listValue['advert_time'];
                    $batch = ++$tKey;
                    if ($advert_key == 'A1') {
                        $batch = $batch * 2 - 1;
                    } elseif ($advert_key == 'A2') {
                        $batch *= 2;
                    }
                    OrderThrowProgramList::updateAll(['start_at' => $start_at, 'end_at' => $start_at + $listValue['advert_time'], 'batch' => $batch], ['id' => $listValue['id']]);
                    break;
                }
            }
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage());
            return false;
        }
    }

   /*
    * 加载节目单数据
    * @param string advert_key 广告位标识
    * @param string area_id 地区ID
    * @param string resource 资源资质
    * @param string start_at 开始时间
    * @param string end_at 结束时间
    * @param array advert_time 广告时长
    * @param json resource_attribute 资源数据
    */
    public function loadProgramData($advert_key,$area_id,$resource,$start_at,$end_at,$advert_time,$resource_attribute){
        $resource_attribute = json_decode($resource_attribute,true);
        $programData['startTime'] = sprintf("%02d", intval($start_at / 60)) . ":" . sprintf("%02d", intval($start_at % 60));
        $programData['endTime'] = sprintf("%02d", intval($end_at / 60)) . ":" . sprintf("%02d", intval($end_at % 60));
        $programData['type'] = $resource_attribute[$advert_key]['type'];
        $programData['url'] = $resource;
        $programData['codeLink'] = '';
        $programData['size'] = $resource_attribute[$advert_key]['size'];
        $programData['sha1Sum'] = $resource_attribute[$advert_key]['sha1Sum'];
        $programData['name'] = $resource_attribute[$advert_key]['name']."_".$area_id."_".$advert_key;
        $programData['duration'] = $advert_time;
        return $programData;
    }

    /*
     * 推送节目单到队列
     * */
    public function pushProgramList(){
        ToolsClass::printLog("order push",'开始推送');
        $todayDate = date('Y-m-d', strtotime("+1 day"));
        //获取所需要更新的街道，按地区分组
        $throwProgramData = OrderThrowProgram::find()->where(['and',['<=','date',$todayDate],['>=','end_date',$todayDate]])->select("id,advert_key,area_id")->asArray()->all();
        //查询出今天新增屏幕的街道
        if(empty($throwProgramData)){
            return false;
        }
        $programId = array_column($throwProgramData,'id');
        //重组节目单数据
        foreach($throwProgramData as $programValue){
            $pushAreaId[] = $programValue['area_id'];
            $reformThrowProgramData[$programValue['id']] = [
                'area_id' => $programValue['area_id'],
                'advert_key' => $programValue['advert_key']
            ];
        }
        //按地区查询出所有类型的广告节目单的父id
        $throwProgramDataList = OrderThrowProgramList::find()->where(['and',['program_id' => $programId],['>','batch',0]])->select('order_id,program_id,start_at,end_at,resource,resource_attribute,advert_time')->orderBy('order_id asc')->asArray()->all();
        //按地区重组节目单数据
        $reformThrowProgramDataList = [];
        $advertOrderId = [];
        foreach($throwProgramDataList as $programListKey => $programListValue){
            $area_id = $reformThrowProgramData[$programListValue['program_id']]['area_id'];
            $advert_key = $reformThrowProgramData[$programListValue['program_id']]['advert_key'];
//            if($advert_key == 'A1' || $advert_key == 'A2'){
//                $edit_advert_key = 'A';
//            }else{
//                $edit_advert_key = $advert_key;
//            }
            $advertOrderId[$area_id][] = $programListValue['order_id'];
            $reformThrowProgramDataList[$area_id][$advert_key][] = $this->loadProgramData($advert_key,$area_id,$programListValue['resource'],$programListValue['start_at'],$programListValue['end_at'],$programListValue['advert_time'],$programListValue['resource_attribute']);
        }
        $pushAreaId = array_unique($pushAreaId);
        foreach ($pushAreaId as $value) {
            // 把组合好的数据写入数据库,并把需要推送的数据写入redis
            try {
                $order_id = isset($advertOrderId[$value]) ? implode(",",array_unique($advertOrderId[$value])) : '';
                $reformValue = isset($reformThrowProgramDataList[$value]) ? json_encode($reformThrowProgramDataList[$value]) : '';
                $result = Yii::$app->throw_db->createCommand("insert into yl_order_throw_program_detail(area_id,content,order_id) values ('{$value}','{$reformValue}','{$order_id}')  ON DUPLICATE KEY UPDATE content = '{$reformValue}',order_id='{$order_id}'")->execute();
                if ($result) {
                    // 内容有变更时推送到发布系统
                    Redis::getInstance(5)->rpush('push_area_list',$value);
                }
            } catch (\Exception $e) {
                ToolsClass::printLog('order push',$e->getMessage());
                Yii::error($e->getMessage());
            }
        }
        Redis::getInstance(5)->del('order_push_list');
        ToolsClass::printLog("order push",'SUCCESS');
    }

    public function getDate(){
        return $this->hasOne(OrderDate::className(),['order_id'=>'id']);
    }

}
