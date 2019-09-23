<?php

namespace cms\modules\member\controllers;
use cms\modules\examine\models\search\ShopScreenReplaceSearch;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberLower;
use cms\modules\member\models\MemberShopApplyCount;
use cms\modules\member\models\search\MemberShopApplyCountSearch;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\Member;
use cms\modules\member\models\search\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\SystemAddress;
use cms\modules\member\models\search\MemberLowerSearch;
use cms\modules\shop\models\search\ShopSearch;
use cms\modules\member\models\MemberBank;
use cms\modules\member\models\search\MemberBankSearch;
use cms\core\CmsController;
use cms\models\ScreenRunTimeShopSubsidy;
use cms\modules\account\models\search\ScreenRunTimeShopSubsidySearch;
use cms\modules\account\models\search\ScreenRunTimeByMonthSearch;
use cms\models\ScreenRunTimeByMonth;
use common\libs\CsvClass;
/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends CmsController
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        /*if(isset($arr['search']) && $arr['search'] == 0){
            $DataArr = $searchModel->search($arr,1)->asArray()->all();
            //ToolsClass::p($DataArr);die;
            if(!empty($DataArr)){
                $title=['序号','姓名','身份证号','联系电话','所属地区','业务区域','收益总额','联系商家数量','联系LED数量','安装商家数量','安装LED数量','是否为内部人员','是否为电工','是否为内部电','是否为合作推广人'];
                $csv=Member::CsvExport($DataArr);
                $file_name="人员信息".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($csv,$title,$file_name);
            }
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }*/


        if(isset($arr['search']) && $arr['search'] == 0){
            // ini_set("memory_limit","-1");
            $file_name = "Member".date("mdHis",time()).".csv";
            $DataCount = $searchModel->search($arr,1)->count();

            if($DataCount == 0){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','姓名','身份证号','联系电话','所属地区','业务区域','收益总额','联系商家数量','联系LED数量','安装商家数量','安装LED数量','是否为内部人员','是否为电工','是否为内部电','是否为合作推广人'];
            $count=ceil($DataCount/1000);
            $j=0;
            for($i=1;$i<=$count;$i++){
                $searchModel->offset=$j;
                $searchModel->limit=1000;
                $data=$searchModel->search($arr,2);
                $j=$i*1000;
                //处理csv要导出的数据
                $CsvData = CsvClass::getMemberIndexData($data);
                if($i==1){
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name);
                }else{
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name,false);
                }
                unset($CsvData);
            }
            CsvClass::CsvDownload($file_name);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 地区切换
     */
    public function actionAddress(){
        $parent_id = Yii::$app->request->post('parent_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        return json_encode($adrsModel::getAreasByPid($parent_id),true);
    }
    /**
     * Displays a single Member model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * 身份证审核
     */
    public function actionExamineCard(){
        $arr = Yii::$app->request->post();
        $model = MemberInfo::findOne(['member_id'=>$arr['member_id']]);
        //如果已经审核过则直接返回
        if($model->examine_status == 1 || $model->examine_status == 2){
            return 5;
        }
        if($model){
            $status = $arr['type'] == 'pass' ? 1 : 2;
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = MemberInfo::saveInfo($model, $status, $desc);
            return $res == true ? 1 : 2;
        }else{
            return 0;
        }
    }
    /**
     * 伙伴信息
     */
    public function actionPartner($id){
        $searchModel = new MemberSearch();
        $searchModel->parent_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //ToolsClass::p($searchModel);die;
        return $this->render('partner',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 商家信息
     */
    public function actionShop($id){
        $searchModel = new ShopSearch();
        $searchModel->member_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('shop',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 我的LED信息
     */
    public function actionLed(){
        $searchModel = new ShopSearch();
        $id = Yii::$app->request->get('id');
        $searchModel->member_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('led',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 我的银行卡绑定信息
     */
    public function actionBank(){
        $searchModel = new MemberBankSearch();
        $id = Yii::$app->request->get('id');
        $searchModel->member_id = $id;
        $searchModel->type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('bank', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //对公账户信息
    public function actionCombank(){
        $searchModel = new MemberBankSearch();
        $id = Yii::$app->request->get('id');
        $searchModel->member_id = $id;
        $searchModel->type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('combank', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 获取屏幕状态
     */
    public function actionScreen($shop_id){
        $screenModel = new ScreenSearch();
        $screenModel->shop_id = $shop_id;
        $dataProvider = $screenModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('screen', [
            'screenModel' => $screenModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 获取屏幕状态
     */
    public function actionInside($id,$inside){
        $res = Member::updateAll(['inside'=>$inside],['id'=>$id]);
        return $res;
    }

    /**
     * 签到管理员设置
     */
    public function actionSignSetup($id,$sign){
        $res = Member::getSignAdmin($id,$sign);
        return $res;
    }

    /**
     * 安装信息
     */
    public function actionInstallInformation($id){
        $searchModel = new ShopScreenReplaceSearch();
        $searchModel->install_member_id = $id;
        $searchModel->replace = 2;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $installObj = $searchModel->search($arr,1)->asArray()->all();
            if(empty($installObj)){
                return $this->render('installlist',[
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','维护类型','商家编号','商家名称','所属地区','详细地址','安装屏幕数','安装人姓名','安装人电话','申请更换时间','安装完成时间','状态'];
            foreach($installObj as $k=>$v){
                $csv[$k]['id']=$v['id'];//序号
                $csv[$k]['maintain_type']=ShopScreenReplace::getMaintainType($v['maintain_type']);//维护类型
                $csv[$k]['shop_id']=$v['shop_id'];//商家编号
                $csv[$k]['shop_name']=$v['shop_name'];//店铺名称
                $csv[$k]['shop_area_name']=$v['shop_area_name'];//店铺所在地区
                $csv[$k]['shop_address']=$v['shop_address'];//详细地址
                $csv[$k]['replace_screen_number']=$v['replace_screen_number'];//安装屏幕数
                $csv[$k]['install_member_name']=$v['install_member_name'];//安装人姓名
                $csv[$k]['mobile']=$v['member']['mobile'];//安装人电话
                $csv[$k]['create_at']=$v['create_at'];//申请更换时间
                $csv[$k]['install_finish_at']=$v['install_finish_at'];//安装完成时间
                $csv[$k]['status']=ShopScreenReplace::getStatus($v['status']);//安装完成时间
            }
            $file_name="member_install_".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('installlist',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 更换屏幕信息
     */
    public function actionChangescreenlist($id)
    {
        $searchModel = new ShopScreenReplaceSearch();
        $searchModel->install_member_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('changescreenlist',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //业绩排行
    public function actionRanking(){
        $searchModel = new MemberShopApplyCountSearch();
        $map=Yii::$app->request->queryParams;
        if(isset($map['search']) && $map['search'] == 0){
            $Data = $searchModel->search($map,1)->asArray()->all();
            if(!empty($Data)){
                $CsvArr=Member::ExportCsv($Data);
                $file_name="Ranking".date("mdHis",time()).".csv";
                $title=['序号','姓名','联系电话','所属地区','业务区域','已安装商家数量','已安装LED数量','待安装商家数量','待安装LED数量'];
                ToolsClass::Getcsv($CsvArr,$title,$file_name);
            }
            $dataProvider = $searchModel->search($map);
            return $this->render('ranking',[
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        $dataProvider = $searchModel->search($map);
        return $this->render('ranking',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 每月维护费用支出
     */
    public function actionMaintainPrice(){
        $searchModel = new ScreenRunTimeShopSubsidySearch();
        $searchModel->type=1;
        $map=Yii::$app->request->queryParams;
        //支出总额
        $TotalPrice= ToolsClass::priceConvert(ScreenRunTimeShopSubsidy::find()->sum('price'));
        $dataProvider = $searchModel->search($map);
        if(isset($map['search']) && $map['search'] == 0){
            $DataCount = $searchModel->search($map,1)->count();
            if($DataCount == 0){
                return $this->render('maintain-price', [
                    'TotalPrice' => $TotalPrice,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','商家编号','商家名称','所属地区','法人ID','法人姓名','法人手机号','维护费用时间周期','屏幕数量','应发维护费用','实发维护费用','是否发放'];
            $file_name="MaintainPrice".date("mdHis",time()).".csv";
            $count=ceil($DataCount/1000);
            $j=0;
            for($i=1;$i<=$count;$i++){
                $searchModel->offset=$j;
                $searchModel->limit=1000;
                $data=$searchModel->search($map,2);
                $j=$i*1000;
                //处理csv要导出的数据
                $CsvData = CsvClass::getMaintainPrice($data);
                if($i==1){
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name);
                }else{
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name,false);
                }
                unset($CsvData);
            }
            CsvClass::CsvDownload($file_name);
        }

        return $this->render('maintain-price', [
            'TotalPrice' => $TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //电费详情，各个屏幕的电费详情
    public function actionMaintainPriceView($shop_id,$date)
    {
        $searchModel = new ScreenRunTimeByMonthSearch();
        $searchModel->shop_id=$shop_id;
        $searchModel->date=$date;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $TotalPrice= ToolsClass::priceConvert(ScreenRunTimeByMonth::find()->where(['shop_id'=>$shop_id])->sum('price'));
        return $this->renderPartial('maintain-price-view', [
            'TotalPrice'=>$TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 是否发放电费
     */
    public function actionMaintainPriceStatus(){
        $data=Yii::$app->request->post();
        $model=ScreenRunTimeShopSubsidy::findOne(['id'=>$data['id']]);
        $model->status=$data['status']==1?2:1;
        if($model->save())
            return json_encode(['code'=>'1','msg'=>'操作成功']);
        return json_encode(['code'=>'2','msg'=>'操作失败']);
    }


    /**
     * @param $shop_id
     * @return string
     * 修改屏幕安装费用
     */
    public function actionMaintainPriceUp($shop_id,$date)
    {
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            $price=0;
            foreach($data['price'] as $k=>$v){
                if($v==''){
                    return json_encode(['code'=>3,'msg'=>'ID：'.$k.' 屏幕维护费用为空']);
                }
                if(is_numeric($v)){
                    if($v<0){
                        return json_encode(['code'=>3,'msg'=>'ID：'.$k.' 屏幕维护费用不能小于0']);
                    }
                    if(strlen(str_replace(".","",strstr($v, '.')))>2){
                        return json_encode(['code'=>3,'msg'=>'ID：'.$k.' 小数点后最多为两位']);
                    }
                }
                if(!is_numeric($v)){
                    return json_encode(['code'=>3,'msg'=>'ID：'.$k.' 屏幕维护费用必须为数字']);
                }
                $price +=$v;
                ScreenRunTimeByMonth::updateAll(['price'=>$v*100],['id'=>$k]);
            }
            ScreenRunTimeShopSubsidy::updateAll(['price'=>$price*100],['shop_id'=>$shop_id,'date'=>$date]);
            return json_encode(['code'=>'1','msg'=>'修改成功']);
        }
        $searchModel = new ScreenRunTimeByMonthSearch();
        $searchModel->shop_id=$shop_id;
        $searchModel->date=$date;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $TotalPrice= ToolsClass::priceConvert(ScreenRunTimeByMonth::find()->where(['shop_id'=>$shop_id,'date'=>$date])->sum('price'));
        return $this->renderPartial('maintain-price-up', [
            'shop_id'=>$shop_id,
            'date'=>$date,
            'TotalPrice'=>$TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //离职人员设置
    public function actionLeaveWireman(){
        $date = Yii::$app->request->get();
        $res = Member::updateAll(['quit_status'=>$date['quit_status']],['id'=>$date['id']]);
        return $res;
    }

    //兼职业务人员设置
    public function actionPartTimeBusiness(){
        $date = Yii::$app->request->get();
        $res = Member::updateAll(['part_time_business'=>$date['status']],['id'=>$date['id']]);
        return $res;
    }
    /**
     * Finds the Member model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
