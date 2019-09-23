<?php

namespace cms\modules\member\controllers;

use cms\models\OrderMessage;
use cms\modules\member\models\OrderArea;
use cms\modules\member\models\OrderDate;
use cms\core\CmsController;
use cms\modules\member\models\MemberInvoice;
use cms\modules\member\models\OrderPlayView;
use cms\modules\member\models\OrderPlayViewArea;
use cms\modules\member\models\OrderPlayViewDate;
use cms\modules\member\models\ReportMongo;
use cms\modules\member\models\search\MemberInvoiceSearch;
use common\libs\Redis;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\Order;
use cms\modules\member\models\search\OrderSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\member\models\search\ReportSearch;
use cms\modules\screen\models\ShopScreenAdvertMaintain;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends CmsController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->AdvertSearch(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
     * 查看订单详情
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        //订单付款信息
        $payMsg = OrderMessage::find()->where(['order_id' => $id, 'type' => 1])->select('desc,create_at')->asArray()->all();
        //订单投放状态信息
        $throwMsg = OrderMessage::find()->where(['order_id' => $id, 'type' => 2])->select('desc,reject_reason,create_at')->asArray()->all();
        //订单时间
        $orderDate = OrderDate::find()->where(['order_id' => $id])->select('start_at,end_at,is_update')->asArray()->one();
        $orderDate['datenum'] = OrderDate::diffBetweenTwoDays($orderDate['start_at'], $orderDate['end_at']);
        return $this->render('detail', [
            'id'=>$id,
            'model' => $model,
            'payMsg' => $payMsg,
            'throwMsg' => $throwMsg,
            'orderDate' => $orderDate,
        ]);
    }

    //修改广告时间
    public function actionUporderdate()
    {
        $array = Yii::$app->request->post();
        $sre = OrderDate::checkDateTime($array);
//        $sre = OrderDate::updateAll(['start_at'=>$array['start_at'],'end_at'=>$array['end_at']],['order_id'=>$array['order_id']]);
        return json_encode($sre);
    }

    /**
     * @return string
     * 合同申请
     */
    public function actionContract()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,6);
        return $this->render('contract', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *发票
     * invoice
     */
    public function actionInvoice()
    {
        $searchModel = new MemberInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('invoice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 发票详情
     */
    public function actionInvoiceinformation($id)
    {
        //发票的详细信息
        $model = MemberInvoice::findOne(['id'=>$id]);
        //查询发票下的所有订单
        $OrderRes = Order::find()->where(['is_billing' => $id])->asArray()->all();
        return $this->renderPartial('invoiceinformation', [
            'model' => $model,
            'OrderRes' => $OrderRes
        ]);
        //  ToolsClass::p($model);
    }

    /**
     * 确认开票
     */
    public function actionOpeninvoice()
    {
        $id = Yii::$app->request->post('id');
        if (MemberInvoice::UpdateAll(['status' => 2], ['id' => $id])) {
            return json_encode(['code' => 1, 'msg' => '开票成功']);
        } else {
            return json_encode(['code' => 2, 'msg' => '开票失败']);
        }
    }

    /**
     * 物流详情
     */
    public function actionLogisticsinformation($id)
    {
        $model = MemberInvoice::findOne(['id'=>$id]);
        $wlinformation = MemberInvoice::getWlInfo($model['logistics_name'], $model['tracking_number']);
        //ToolsClass::p($wlinformation);
        return $this->renderPartial('logisticsinformation', [
            'model' => $model,
            'wlinformation' => $wlinformation
        ]);
    }

    /**
     * 修改物流信息
     */
    public function actionConfirmwl()
    {
        $data = Yii::$app->request->post();
        if (MemberInvoice::UpdateAll(['tracking_number' => $data['tracking_number'], 'logistics_name' => $data['logistics_name']], ['id' => $data['id']])) {
            return json_encode(['code' => 1, 'msg' => '操作成功']);
        } else {
            return json_encode(['code' => 2, 'msg' => '操作失败']);
        }
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionContactstatus()
    {
        $data = Yii::$app->request->post();
        if (Order::updateAll(['contact_number' => $data['contact_number'], 'contact_status' => 2], ['id' => $data['id']])) {
            return json_encode(['code' => 1, 'msg' => '成功']);
        } else {
            return json_encode(['code' => 2, 'msg' => '失败']);
        }

    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //修改时间查看排期
    public function actionPaiqi()
    {
//        $model = $this->findModel($id);
        $array = Yii::$app->request->get();
        $datepage = isset($array['timepage']) ? $array['timepage'] : 1;
        $areapage = isset($array['areapage']) ? $array['areapage'] : 1;
        $begintime = strtotime($array['start']);
        $endtime = strtotime($array['end']);
        for ($start = $begintime; $start <= $endtime; $start += 24 * 3600) {
            $datelist[] = date("Y-m-d", $start);
        }
        $datepageSize = 8;
        if ($datepage <= 0) {
            $datepage = 1;
        } elseif ($datepage >= ceil(count($datelist) / $datepageSize)) {
            $datepage = ceil(count($datelist) / $datepageSize);
        }
        $datepagelist = array_slice($datelist, ($datepage - 1) * $datepageSize, $datepageSize);
//        $totalPage = ceil($datetotal/$pageSize);
//        $arr['datetotal'] = count($datelist);
//        $arr['pageSize'] = $pageSize;
//        $arr['totalPage'] = $totalPage;

        $areaModel = OrderArea::findOne(['order_id' => $array['id']]);//订单地区
        if (isset($areaModel->area_id)) {
            $streetArr = explode(',', $areaModel->area_id);
            $areapageSize = 10;
            if ($areapage <= 0) {
                $areapage = 1;
            } elseif ($datepage >= ceil(count($streetArr) / $areapageSize)) {
                $areapage = ceil(count($streetArr) / $areapageSize);
            }
            $areapagelist = array_slice($streetArr, ($areapage - 1) * $areapageSize, $areapageSize);
        }
        $oldDatelist = OrderDate::getOrderDateSeries($array['id']);//原订单时间
        $order = Order::findOne(['id' => $array['id']]);
        $adverkey = strtolower($order->advert_key);
        $times = ToolsClass::minuteCoverSecond($order->advert_time);
        $number = $order->number;
        $redisObj = RedisClass::init(4);
        $difdate = array_diff($datepagelist, $oldDatelist);  //差异日期
        $buystreet = explode(',',$areaModel->street_area);
        if(empty($difdate)){
//            var_dump(111);
            foreach ($datepagelist as $kd => $vd) {
                foreach ($areapagelist as $ka => $va) {
                    $resule[$va][$vd] = '充足';
                }
            }
        }else{

            if ($areaModel->area_type == 4) {
//                var_dump(222);
                foreach ($datepagelist as $kd => $vd) {
                    $dates = preg_replace('/-/', '', $vd);
                    foreach ($areapagelist as $ka => $va) {
//                        $rediskey[] = 'system_advert_space_rate_' . $adverkey . ':' . $dates . ':' . $va;
                        $rediskey["advert_cell_status:{$vd}"][] = ToolsClass::reduceBigMapKey($adverkey,$times,$number,$dates);
                    }
                }
                $system_advert_space_rate = Redis::getBitMulti($rediskey);
                $k = 0;
                foreach ($datepagelist as $kd => $vd) {
                    foreach ($areapagelist as $ka => $va) {
//                        if($system_advert_space_rate[$k] == null){
//                            $resule[$va][$vd] = '充足';
//                        }else{
//                            $arraynew = explode(',',$system_advert_space_rate[$k]);
//                            if(in_array($vd,$oldDatelist)){
//                                $resule[$va][$vd] = '充足';
//                            }else{
                                if(!$system_advert_space_rate[$k]){
                                    $resule[$va][$vd] = '充足';
                                }else{
                                    $resule[$va][$vd] = '无余量';
                                }
//                            }
//                        }
                        $k++;
                    }
                }
            } else {
//                var_dump(333);
                foreach ($areapagelist as $ka => $va) {
                    foreach ($datepagelist as $kd => $vd) {
                    $dates = preg_replace('/-/', '', $vd);
                        $resule[$va][$vd] = '无余量';
                        foreach($buystreet as $kb =>$vb){
                            if(strpos($vb,$va) !==false){
                                $res = $redisObj->executeCommand('getbit',["advert_cell_status:{$vb}",ToolsClass::reduceBigMapKey($adverkey,$times,$number,$dates)]);
                                if($res!=1){
                                    $resule[$va][$vd] = '充足';
                                    break;
                                }
                            }
                        }
                        #$rediskey[] = 'system_advert_space_rate_' . $adverkey . ':' . $dates . ':' . $va.':'.$times.':'.$number;
                    }
                }
//                $system_advert_space_rate = $redisObj->executeCommand('mget', $rediskey);
//                $k = 0;
//                $newarea = [];
//                foreach ($datepagelist as $kd => $vd) {
//                    foreach ($areapagelist as $ka => $va) {
//                        foreach($buystreet as $kb =>$vb){
//                            if(strpos($vb,$va) !==false){
//                                $newarea[] =$vb;
//                            }
//                        }
//                        $arraynew = explode(',',$system_advert_space_rate[$k]);
//                        if(in_array($vd,$oldDatelist)){
//                            $resule[$va][$vd] = '充足';
//                        }else{
//                            $areadiff = array_diff($newarea,$arraynew);
//                            if(!empty($areadiff)){
//                                $resule[$va][$vd] = '充足';
//                            }else{
//                                $resule[$va][$vd] = '无余量';
//                            }
//                        }
//                        $k++;
//                    }
//                }
            }
        }
        return $this->renderPartial('paiqi', [
            'array' => $array,
            'datepagelist' => $datepagelist,
            'resule' => $resule,
            'areapage' => $areapage,
            'datepage' => $datepage,
        ]);

    }


    /**
     * 到达率报告
     */
    public function actionArrivalRateReport($id){
        $searchModel = new ReportSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['ReportSearch']['shop_id']) && !empty($arr['ReportSearch']['shop_id'])){
            $searchModel->shop_id = $arr['ReportSearch']['shop_id'];
        }
        if(isset($arr['ReportSearch']['shop_name']) && !empty($arr['ReportSearch']['shop_name'])){
            $searchModel->shop_name = $arr['ReportSearch']['shop_name'];
        }
        if(isset($arr['ReportSearch']['arrival_rate']) && !empty($arr['ReportSearch']['arrival_rate'])){
            $searchModel->arrival_rate = $arr['ReportSearch']['arrival_rate'];
        }
        $table = 'order_arrival_report';
        if(isset($arr['search']) && $arr['search'] == 0){
            $asArr = $searchModel->getArrivalRateReportSearch($arr,$table,$id,$export=1);
            if(!empty($asArr)){
                $title=['地区名称','街道','店铺','店铺ID','到达率'];
                foreach ($asArr as $k=>$v){
                    $Csv[$k]['area_name']=$v['area_name'];//地区名称
                    $Csv[$k]['street_name']=$v['street_name'];//街道
                    $Csv[$k]['shop_name']=$v['shop_name'];//店铺
                    $Csv[$k]['shop_id']=$v['shop_id'];//店铺ID
                    $Csv[$k]['arrival_rate']=$v['arrival_rate'].'%';//到达率
                }
                ToolsClass::Getcsv($Csv,$title,"ArrivalRateReport".date("mdHis",time()).".csv");die;
            }
            $asArr = $searchModel->getArrivalRateReportSearch($arr,$table,$id);
            return $this->render('arrival-rate-report', [
                'id'=>$id,
                'asArr' => $asArr,
                'searchModel' => $searchModel,
                'dataProvider' => $asArr,
            ]);
        }
        $asArr = $searchModel->getArrivalRateReportSearch($arr,$table,$id);
        return $this->render('arrival-rate-report', [
            'id'=>$id,
            'asArr' => $asArr,
            'searchModel' => $searchModel,
            'dataProvider' => $asArr,
        ]);
    }

    /**
     * 到达率报告详情
     * @param $id
     * @return string
     */
    public function actionArrivalRateReportView($id){
        $model = new ReportMongo();
        $data = $model->getArrivalRateReportView($id,'order_arrival_report');
        $redisObj = Yii::$app->redis;
        $redisObj->select(3);
        $key = "order_device_list:".$data['order_id'];
        $order_device_list = $redisObj->SMEMBERS($key);
        return $this->renderPartial('arrival-rate-report-view', [
            'order_device_list'=>$order_device_list,
            'data'=>$data,
        ]);
    }

    public function actionBroadcastRateReport($id){
        $OrderDateModel =OrderDate::findOne(['order_id'=>$id]);
        $OrderModel = new Order();
        $orderData = $OrderModel->find()->where(['id'=>$id])->select('id,rate,screen_number,total_day')->asArray()->one();
        if(!$OrderDateModel){
            $dateArr=[];
        }else{
            $dateArr = OrderDate::prDates($OrderDateModel->start_at,$OrderDateModel->end_at);
        }
        $searchModel = new ReportSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['ReportSearch']['shop_id']) && !empty($arr['ReportSearch']['shop_id'])){
            $searchModel->shop_id = $arr['ReportSearch']['shop_id'];
        }
        if(isset($arr['ReportSearch']['shop_name']) && !empty($arr['ReportSearch']['shop_name'])){
            $searchModel->shop_name = $arr['ReportSearch']['shop_name'];
        }
        $table = 'order_arrival_report';
        $asArr = $searchModel->getBroadcastRateReportSearch($arr,$table,$id,$orderData);
        $BroadcastData =$searchModel->getBroadcastRateReportDateSearch($id);

        return $this->render('broadcast-rate-report', [
            'id'=>$id,
            'asArr' => $asArr,
            'dateArr'=>$dateArr,
            'BroadcastData'=>$BroadcastData,
            'searchModel' => $searchModel,
        ]);
    }

    //监播报告
    public function actionRateReport($id){
        $order_id = $id;
        $orders = Order::findOne(['id'=>$order_id]);
        return $this->render('rate-report', [
            'order_id' => $order_id,
            'orders' => $orders,
        ]);
    }
    //查数据
    public function actionRateReportData($order_id){
        $base = OrderPlayView::getFields($order_id,'order_code,salesman_name,custom_service_name,start_at,end_at,advert_name,advert_time,advert_rate,throw_area,total_order_play_number,total_play_number,total_play_time,total_arrival_rate,total_play_rate,total_watch_number,total_people_watch_number,total_no_repeat_watch_number,people_watch_number,total_radiation_number,throw_shop_number,throw_screen_number,throw_mirror_number,screen_run_time,throw_city_number,throw_area_number,throw_street_number,give_shop_number,give_screen_number,give_play_number,give_watch_number,give_radiation_number');
        $rand = OrderPlayViewDate::getRank($order_id);
        $area = OrderPlayViewArea::getArea($order_id);
        return json_encode(['status'=>200,'data'=>[
            'base' => $base,
            'rand'=>$rand,
            'area' => $area,
        ]]);
    }

    //下发
    public function actionLowerHair(){
        $data = Yii::$app->request->post();
        $LowerHairModel = new ShopScreenAdvertMaintain();
        return $LowerHairModel->getLowerHair($data);
    }
    //取消下发
    public function actionCancelLowerHair(){
        $data = Yii::$app->request->post();
        $LowerHairModel = new ShopScreenAdvertMaintain();
        return $LowerHairModel->getCancelLowerHair($data);
    }
}

