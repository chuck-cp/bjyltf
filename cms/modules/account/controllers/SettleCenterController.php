<?php

namespace cms\modules\account\controllers;

use cms\models\SystemAccount;
use cms\modules\account\models\OrderBrokerage;
use cms\core\CmsController;
use cms\modules\account\models\search\OrderBrokerageSearch;
use cms\modules\member\models\Order;
use cms\modules\examine\models\search\ShopScreenReplaceSearch;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\member\models\Member;
use cms\modules\member\models\MemberInstallSubsidyList;
use common\libs\ToolsClass;
use Yii;
use cms\modules\account\models\LogPayment;
use cms\modules\account\models\search\LogPaymentSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\account\models\search\ShopSearch;
use cms\models\OrderMessage;
use cms\modules\member\models\OrderDate;
use cms\modules\member\models\OrderArea;
use cms\models\AdvertPosition;
use common\libs\RedisClass;
use console\models\MemberAccount;
use console\models\MemberAccountCount;
use cms\modules\account\models\search\MemberInstallSubsidyListSearch;
use common\libs\CsvClass;
/**
 * SettleCenterController implements the CRUD actions for LogPayment model.
 */
class SettleCenterController extends CmsController
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
     * Lists all LogPayment models.
     * @return mixed
     */
    public function actionCollection()
    {
        $searchModel = new LogPaymentSearch();
        $searchModel->pay_status = 1;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        //总收益
        $totalMoney = SystemAccount::getTotalMoney();
        if(isset($arr['search']) && $arr['search'] == 0){
            $DataArr = $searchModel->search($arr,1)->asArray()->all();
            if(empty($DataArr)){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'totalMoney' => $totalMoney,
                ]);
            }
            $title=['序号','收款时间','用户ID','用户姓名','支付类型','订单号','订单总额','最终价格','本次收款金额','流水号','支付方式','第三方平台流水号','业务合作人支出','广告实际收入','合作人ID','合作人','合作人手机','对接人ID','对接人'];//,'优惠方式'
            foreach($DataArr as $k=>$v){
                $csv[$k]['id']=$v['id'];
                $csv[$k]['pay_at']=$v['pay_at'];
                $csv[$k]['member_id']=$v['orderInfo']['member_id'];
                $csv[$k]['member_name']=$v['orderInfo']['member_name'];
                $csv[$k]['pay_style']=LogPayment::getPayStyle(true,$v['pay_style']);
                $csv[$k]['order_code']=$v['order_code'];
                $csv[$k]['order_price']=$v['pay_style']==2?'---':ToolsClass::priceConvert($v['orderInfo']['order_price']);
//                $csv[$k]['preferential_way']=$v['orderInfo']['preferential_way']?$v['orderInfo']['preferential_way']:'无优惠';
                $csv[$k]['final_price']=ToolsClass::priceConvert($v['orderInfo']['final_price']);
                $csv[$k]['price']=ToolsClass::priceConvert($v['price']);
                $csv[$k]['serial_number']=$v['serial_number'].",";
                $csv[$k]['pay_type']=LogPayment::getPayType(true,$v['pay_type']);
                $csv[$k]['other_serial']=$v['other_serial'].",";
                $csv[$k]['total']=ToolsClass::priceConvert($v['brokerage']['total']);
                $csv[$k]['real_income']=ToolsClass::priceConvert($v['brokerage']['real_income']);
                $csv[$k]['salesman_id']=$v['orderInfo']['salesman_id'];
                $csv[$k]['salesman_name']=$v['orderInfo']['salesman_name'];
                $csv[$k]['salesman_mobile']=$v['orderInfo']['salesman_mobile'];
                $csv[$k]['custom_member_id']=$v['orderInfo']['custom_member_id'];
                $csv[$k]['custom_service_name']=$v['orderInfo']['custom_service_name'];
            }
            $file_name="Collection".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalMoney' => $totalMoney,
        ]);
    }

    //线下结算中心
    public function actionOffline(){
        $searchModel = new LogPaymentSearch();
        $arr = Yii::$app->request->queryParams;
        //此条件为支付方式为线下付款
        $arr['LogPaymentSearch']['pay_type']=4;
        $dataProvider = $searchModel->search($arr,0);
        if(isset($arr['search']) && $arr['search'] == 0){
            $ledDataObj = $searchModel->search($arr,1)->asArray()->all();
            if(empty($ledDataObj)){
                return $this->render('offline', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['用户ID','用户姓名','业务合作人ID','业务合作人','业务合作人手机号','广告对接人ID','广告对接人','广告对接人手机号','订单号','订单提交时间','订单金额','最终价格','支付类型','付款金额','交易码','付款状态'];//,'优惠方式'
            foreach($ledDataObj as $k=>$v){
                $csv[$k]['member_id']=$v['orderInfo']['member_id'];
                $csv[$k]['member_name']=$v['orderInfo']['member_name'];
                $csv[$k]['salesman_id']=$v['orderInfo']['salesman_id'];
                $csv[$k]['salesman_name']=$v['orderInfo']['salesman_name'];
                $csv[$k]['salesman_mobile']=$v['orderInfo']['salesman_mobile']."\t";
                $csv[$k]['custom_member_id']=$v['orderInfo']['custom_member_id'];
                $csv[$k]['custom_service_name']=$v['orderInfo']['custom_service_name'];
                $csv[$k]['custom_service_mobile']=$v['orderInfo']['custom_service_mobile'];
                $csv[$k]['order_code']=$v['order_code']."\t";
                $csv[$k]['create_at']=$v['orderInfo']['create_at'];
                $csv[$k]['price']=\common\libs\ToolsClass::priceConvert($v['price']);
//                $csv[$k]['preferential_way']=$v['orderInfo']['preferential_way']?$v['orderInfo']['preferential_way']:'无优惠';
                $csv[$k]['final_price']=\common\libs\ToolsClass::priceConvert($v['orderInfo']['final_price']);
                if($v['pay_style']==1){
                    $csv[$k]['pay_style']='全款付款';
                }else if($v['pay_style']==2){
                    $csv[$k]['pay_style']='定金付款';
                }else if($v['pay_style']==3){
                    $csv[$k]['pay_style']='尾款付款';
                }else{
                    $csv[$k]['pay_style']='未设置';
                }
                $csv[$k]['payment_price']=ToolsClass::priceConvert($v['orderInfo']['payment_price']);
                $csv[$k]['payment_code']=$v['payment_code']."\t";
                if($v['pay_status']==0){
                    $csv[$k]['pay_status']='未付款';
                }else{
                    $csv[$k]['pay_status']='已付款';
                }
            }
            $file_name="Offline".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('offline', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LogPayment model.
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
//    public function actionMoney(){
//        $data= Yii::$app->request->get();
//        $array=['price'=>$data['price'],'order_code'=>$data['order_code'],'pay_style'=>$data['pay_style'],'payment_status'=>$data['payment_status']];
//        //$model=['price'=>$array['price'],'count_payment_price'=>''];
//        //$obj = Order::find()->where(['order_code'=>$array['order_code']]);
//        //$obj = Order::findOne(['order_code'=>$array['order_code']]);
//        //return $this->renderPartial('money');
//        return $this->renderPartial('money', [
//            'model' =>$array,
//        ]);
//    }
//    public function actionMoneyajax(){
//        //开启事物
//        $transaction = Yii::$app->db->beginTransaction();
//        try{
//            $data=Yii::$app->request->get();
//            // order_code
//            // price
//            // pay_style
//            // payment_status
//            //处理预付款
//            /*if($data['pay_style']==2 && $data['payment_status']==0){
//                $order=Order::findOne(['order_code' => $data['order_code']]);
//                $count_payment_price=$order['count_payment_price']+$data['count_payment_price'];
//                if($count_payment_price==$data['price']){
//                    Order::updateAll(['count_payment_price'=>$count_payment_price,'payment_status'=>1],['order_code'=>$data['order_code']]);
//                    LogPayment::updateAll(['pay_status'=>1],['id'=>$data['id']]);
//                }else{
//                    Order::updateAll(['count_payment_price'=>$count_payment_price],['order_code'=>$data['order_code']]);
//                }
//                Order::updateAll(['count_payment_price'=>$count_payment_price],['order_code'=>$data['order_code']]);
//            }*/
//            //处理尾款
//            if($data['pay_style']==3 && $data['payment_status']!==1){
//                echo 'aaaa';
//            }
//
//        }catch (Exception $e){
//            Yii::error($e->getMessage(),'error');
//            $transaction->rollBack();
//            return 2;
//        }
//    }

    /**
     * 确认金额
     */
    public function actionConfirm(){
        try{
            $transaction = Yii::$app->db->beginTransaction();
            $data=Yii::$app->request->post();
            //重新计算广告总收入
            SystemAccount::getUpdateTotal($data['price']);
            //根据订单号查询order表的信息
            $Order=Order::find()->where(['order_code' => $data['order_code']])->one();
            if($data['pay_status']==0){
                if($Order['payment_status']==3){
                    return  json_encode(['code'=>3,'msg'=>'订单有误，操作失败！']);
                }
            }
            //当该订单是第一次付款的时候写入Redis 并对 member_account表和member_account_count 的order_number进行累加或者新建
            if($Order['payment_status']==0){
                $OrderDate=OrderDate::find()->where(['order_id' => $Order['id']])->one();
                $OrderArea=OrderArea::find()->where(['order_id' => $Order['id']])->one();
                $AdvertPosition=AdvertPosition::find()->where(['id' => $Order['advert_id']])->one();
                RedisClass::rpush("system_create_order_list",json_encode([
                    'type'=>'create_order',
                    'order_id'=>$Order['id'],
                    'delete_date'=>'',
                    'advert_key'=>strtolower($Order['advert_key']),
                    'rate'=>$Order['number'],
                    'start_at'=>$OrderDate['start_at'],
                    'end_at'=>$OrderDate['end_at'],
                    'area_id'=>$OrderArea['area_id'],
                    'group'=>$AdvertPosition['group'],
                    'bind'=>strtolower($AdvertPosition['bind']),
                    'advert_time'=>$Order['advert_time'],
                    'token'=>md5("wwwbjyltfcom{$Order['advert_time']}{$Order['advert_key']}{$Order['number']}{$Order['member_id']}")
                ]),4);
                //判断数据是否存在 存在就累加 不存就添加
                if(MemberAccount::find()->where(['member_id' => $Order['member_id']])->count()==0){
                    $MemberAccount= new MemberAccount();
                    $MemberAccount->member_id=$Order['member_id'];
                    $MemberAccount->order_number=1;
                    $MemberAccount->save();
                }else{
                    MemberAccount::updateAllCounters( ['order_number' => 1], ['member_id' => $Order['member_id']] );
                }
                if(MemberAccountCount::find()->where(['member_id' => $Order['member_id'],'create_at'=>date('Y-m')])->count()==0){
                    $MemberAccountCount= new MemberAccountCount();
                    $MemberAccountCount->member_id=$Order['member_id'];
                    $MemberAccountCount->order_number=1;
                    $MemberAccountCount->create_at=date('Y-m');
                    $MemberAccountCount->save();
                }else{
                    MemberAccountCount::updateAllCounters( ['order_number' => 1], ['member_id' => $Order['member_id'],'create_at'=>date('Y-m')] );
                }
            }
            if($data['pay_style']==3){
                $payment_status=3;
                Order::updateAll(['payment_status'=>$payment_status,'last_payment_at'=>date('Y-m-d H:i:s')],['order_code'=>$data['order_code']]);
            }else if($data['pay_style']==1){
                $payment_status=3;
                Order::updateAll(['payment_status'=>$payment_status,'payment_at'=>date('Y-m-d H:i:s'),'last_payment_at'=>date('Y-m-d H:i:s')],['order_code'=>$data['order_code']]);
            }else if ($data['pay_style']==2){
                $payment_status=1;
                Order::updateAll(['payment_status'=>$payment_status,'payment_at'=>date('Y-m-d H:i:s')],['order_code'=>$data['order_code']]);
            }
            LogPayment::updateAll(['pay_status'=>1,'pay_at'=>date('Y-m-d H:i:s')],['id'=>$data['id']]);

            //添加订单状态记录
            $OrderMessagedj=new OrderMessage();
            $OrderMessagedj->order_id=$Order['id'];
            $OrderMessagedj->type=1;
            if($data['pay_style']==1){
                $OrderMessagedj->desc='成功付款（线下付款）';
            }else if($data['pay_style']==2){
                $OrderMessagedj->desc='完成首付款（线下付款）';
            }else if($data['pay_style']==3){
                $OrderMessagedj->desc='完成尾款（线下付款）';
            }
            $OrderMessagedj->save();
            $OrderMessagetf=new OrderMessage();
            $OrderMessagetf->order_id=$Order['id'];
            $OrderMessagetf->type=2;
            if($data['pay_style']==1 || $data['pay_style']==2){
                $OrderMessagetf->desc='广告素材待提交';
            }
            $OrderMessagetf->save();
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'确认完成']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    /**
     * Creates a new LogPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LogPayment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LogPayment model.
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
     * Deletes an existing LogPayment model.
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
     * Finds the LogPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LogPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogPayment::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //业务合作人
    public function actionSalesmanpay()
    {
        $searchModel = new OrderBrokerageSearch();
        $map=Yii::$app->request->queryParams;
        //总收益
        $totalMoney = SystemAccount::getTotalMoney();
        $dataProvider = $searchModel->search($map,0);
        if(isset($map['search']) && $map['search'] == 0){
            $data = $searchModel->search($map,1)->asArray()->all();
            if(empty($data)){
                return $this->render('salesmanpay', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'totalMoney' => $totalMoney,
                ]);
            }
            $title=['订单编号','业务合作人ID','业务合作人姓名','业务合作人账号','订单总额','业务合作人佣金','上级提成','配合费','总支出'];
            foreach ($data as $k=>$v){
                $csv[$k]['order_code']=$v['orderInfo']['order_code']."\t";
                $csv[$k]['member_id']=$v['member_id'];
                $csv[$k]['member_name']=$v['member_name'];
                $csv[$k]['member_mobile']=$v['member_mobile'];
                $csv[$k]['order_price']=ToolsClass::priceConvert($v['orderInfo']['order_price']);
                $csv[$k]['member_price']=ToolsClass::priceConvert($v['member_price']);
                $csv[$k]['member_parent_price']=ToolsClass::priceConvert($v['member_parent_price']);
                $csv[$k]['cooperate_money']=ToolsClass::priceConvert($v['cooperate_money']);
                $csv[$k]['total']=ToolsClass::priceConvert($v['total']);
            }
            $file_name="安装费用支出".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('salesmanpay', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalMoney' => $totalMoney,
        ]);
    }

    //合作人详情查看
    public function actionManpay($id){
        $Model = new OrderBrokerage();
        $idinfo = $Model->findOne(['id'=>$id]);
        $payinfo = $Model->objtoarray($idinfo);
        $orderinfo = Order::find()->where(['id'=>$payinfo['order_id']])->select('order_price,order_code')->asArray()->one();
        $payinfo['orderpay'] = $orderinfo['order_price'];
        $payinfo['ordercode'] = $orderinfo['order_code'];
        $payinfo['meminfo']=array();
        $mem = Member::find()->where(['id'=>$payinfo['member_parent_id']])->select('name,mobile')->asArray()->one();
        $payinfo['meminfo']['mid'] = $payinfo['member_parent_id'];
        $payinfo['meminfo']['mobile'] = $mem['mobile'];
        $payinfo['meminfo']['name'] = $mem['name'];
        $payinfo['meminfo']['memprice'] = $payinfo['member_parent_price'];
        $peihememid =explode(',',$payinfo['cooperate_member_id']);
        $payinfo['peihemems']=array();
        foreach($peihememid as $peihek =>$peihev) {
            $peihemem = Member::find()->where(['id' => $peihev])->select('name,mobile')->asArray()->one();
            $payinfo['peihemems'][$peihek]['peiheid'] = $peihev;
            $payinfo['peihemems'][$peihek]['peihemobile'] = $peihemem['mobile'];
            $payinfo['peihemems'][$peihek]['peihename'] = $peihemem['name'];
            $payinfo['peihemems'][$peihek]['peiheprice'] =round($payinfo['cooperate_money']/count($peihememid));
        }
        return $this->renderPartial('manpay', [
            'payinfo' => $payinfo,
        ]);
    }


    /**
     * 安装费用支出
     */
    public function actionInstall(){
        $searchModel = new ShopSearch();
        $searchModel->status =[5,6];
        $map=Yii::$app->request->queryParams;
        /*$data = $searchModel->search($map,1)->asArray()->one();
        ToolsClass::p($data);*/
        $dataProvider = $searchModel->search($map,0);
        if(isset($map['search']) && $map['search'] == 0){
            $file_name = "InstallPrice".date("mdHis",time()).".csv";
            $DataCount = $searchModel->search($map,1)->count();
             if($DataCount == 0){
                return $this->render('install', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['商家编号','商家名称','详细地址','法人ID','法人姓名','法人手机号','申请时间','完成时间','独家买断费用','业务合作人ID','业务合作人','业务合作人手机号','业务合作费用','业务合作人红包','业务合作人总费用','推荐人ID','推荐人','推荐人手机','推荐人奖励金','邀请人ID','邀请人','邀请人手机号','邀请人奖励金','安装人员ID','安装人员','安装人员手机号','安装地区价格','安装屏幕数','安装人员总费用','总支出'];
            $count=ceil($DataCount/1000);
            $j=0;
            for($i=1;$i<=$count;$i++){

                $searchModel->offset=$j;
                $searchModel->limit=1000;
                $j=$i*1000;
                $data=$searchModel->search($map,2);
                //处理csv要导出的数据
                $CsvData = CsvClass::SettleCenterInstllData($data);
                if($i==1){
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name);
                }else{
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name,false);
                }
                unset($CsvData);
            }
            CsvClass::CsvDownload($file_name);
        }
        return $this->render('install', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * 安装费用补贴
     *
     */
    public function actionInstallSubsidy(){
        $searchModel = new MemberInstallSubsidyListSearch();
        $map=Yii::$app->request->queryParams;
        //补贴人数
        $NumberOfSubsidies= MemberInstallSubsidyList::find()->groupBy('subsidy_id')->count();
        //补贴总额
        $TotalSubsidy= ToolsClass::priceConvert(MemberInstallSubsidyList::find()->sum('subsidy_price'));
        //补贴次数
        $CountSubsidyList=MemberInstallSubsidyList::find()->count();
        $dataProvider = $searchModel->search($map);
        if(isset($map['search']) && $map['search'] == 0){
            $DataAll = $searchModel->search($map,1)->asArray()->all();
            if(empty($DataAll)){
                return $this->render('install-subsidy', [
                    'NumberOfSubsidies'=>$NumberOfSubsidies,
                    'TotalSubsidy'=>$TotalSubsidy,
                    'searchModel' => $searchModel,
                    'CountSubsidyList'=>$CountSubsidyList,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','安装人ID','安装人姓名','安装人手机','补贴申请日期','补贴费用','当日收入','补贴原因'];
            foreach ($DataAll as $k=>$v){
                $Csv[$k]['id']=$v['id'];
                $Csv[$k]['install_member_id']=$v['install_member_id'];
                $Csv[$k]['name']=$v['memberNameMobile']['name'];
                $Csv[$k]['mobile']=$v['memberNameMobile']['mobile'];
                $Csv[$k]['create_at']=$v['create_at'];
                $Csv[$k]['subsidy_price']=ToolsClass::priceConvert($v['subsidy_price']);
                $Csv[$k]['income_price']=ToolsClass::priceConvert($v['memberIncomePrice']['income_price']);
                $Csv[$k]['subisdy_desc']=$v['subisdy_desc'];
            }
            $file_name="安装费用补贴".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($Csv,$title,$file_name);
        }
        return $this->render('install-subsidy', [
            'NumberOfSubsidies'=>$NumberOfSubsidies,
            'TotalSubsidy'=>$TotalSubsidy,
            'searchModel' => $searchModel,
            'CountSubsidyList'=>$CountSubsidyList,
            'dataProvider' => $dataProvider,
        ]);
    }

    //换屏费用支出:replace-screen
    public function actionReplaceScreen(){
        $resModel = new ShopScreenReplaceSearch();
        $resModel->replace = 1;
        $map=Yii::$app->request->queryParams;
        $dataProvider = $resModel->search($map,0);
        if(isset($map['search']) && $map['search'] == 0){
            $data = $resModel->search($map,1)->asArray()->all();
            if(empty($data)){
                return $this->render('replace-screen', [
                    'resModel'=>$resModel,
                    'dataProvider'=>$dataProvider,
                ]);
            }
            $title=['序号','安装类型','商家编号','商家名称','所属地区','安装人ID','安装人姓名','安装人电话','申请更换时间','安装完成时间','更换屏幕数','更换屏幕单价','更换屏幕总费用'];
            foreach ($data as $k=>$v){
                $csv[$k]['id']=$v['id'];//编号
                $csv[$k]['maintain_type']=ShopScreenReplace::getMaintainType($v['maintain_type']);//安装类型
                $csv[$k]['shop_id']=$v['shop_id'];//商家编号
                $csv[$k]['shop_name']=$v['shop_name'];//商家名称
                $csv[$k]['shop_address']=$v['shop_address'];//所属地区
                $csv[$k]['install_member_id']=$v['install_member_id'];//安装人ID
                $csv[$k]['install_member_name']=$v['install_member_name'];//安装人姓名
                $csv[$k]['mobile']=$v['member']['mobile'];//安装人电话
                $csv[$k]['create_at']=$v['create_at'];//申请更换时间
                $csv[$k]['install_finish_at']=$v['install_finish_at'];//安装完成时间
                $csv[$k]['replace_screen_number']=$v['replace_screen_number'];//更换屏幕数
                $csv[$k]['install_price']=ToolsClass::priceConvert($v['install_price']/$v['replace_screen_number']);//更换屏幕单价
                $csv[$k]['tolprice']=ToolsClass::priceConvert($v['install_price']);//更换屏幕总费用
            }
            $file_name="换屏费用支出".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }

        return $this->render('replace-screen', [
            'resModel'=>$resModel,
            'dataProvider'=>$dataProvider,
        ]);
    }
}
