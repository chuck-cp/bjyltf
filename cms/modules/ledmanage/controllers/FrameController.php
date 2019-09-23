<?php

namespace cms\modules\ledmanage\controllers;

use cms\models\LogDevice;
use cms\models\SystemOffice;
use cms\modules\examine\models\ShopLogistics;
use cms\modules\member\models\Member;
use cms\modules\member\models\search\MemberSearch;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use Yii;
use cms\modules\ledmanage\models\SystemDeviceFrame;
use cms\modules\ledmanage\models\search\SystemDeviceFrameSearch;
use cms\core\CmsController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FrameController implements the CRUD actions for SystemDeviceFrame model.
 */
class FrameController extends CmsController
{
    /**
     * {@inheritdoc}
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
     * Lists all SystemDeviceFrame models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemDeviceFrameSearch();
        $arr = Yii::$app->request->queryParams;
        $asArr = $searchModel->search($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            //获取总条数
            $DataCount = $searchModel->search($arr,2)->count();
            if($DataCount==0){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'asArr'=>$asArr,
                    'isoutput'=>SystemDeviceFrame::isoutput()
                ]);
            }
            //取得最大页数
            $count=ceil($DataCount/3000);
            $j=0;
            //表头
            $title=['序号','硬件编号','厂家名称','办事处','仓库','规格','材质','品质','NFC','入库时间','入库负责人','出库时间','出库负责人','设备领取人','是否出库','批次','备注'];
            for($i=1;$i<=$count;$i++){
                $arrexport = Yii::$app->request->queryParams;
                $searchModel->offset=$j;
                $searchModel->limit=3000;
                $j=$i*3000;
                $data=$searchModel->search($arrexport,1);
                //处理csv要导出的数据
                $Csv=SystemDeviceFrame::CsvData($data);
                $file_name="Led_".time().'_'.$i.".csv";
                //生成CSV文件并返回文件路径
                $filenameArr[]=ToolsClass::Getcsvzip($Csv,$title,$file_name);
            }
            //进行多个文件压缩并下载zip文件
            ToolsClass::zip($filenameArr,'framecsv.zip');
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'asArr'=>$asArr,
            'isoutput'=>SystemDeviceFrame::isoutput()
        ]);
    }

    //地方库处理切换
    public function actionOffices($kuid='')
    {
        if(empty($kuid)){
            $office = explode(',',Yii::$app->user->identity->office_auth);
            if($office[0] != 0){
                $kuid = $office[0];
            }else{
                $this->layout =false;
                return $this->render('//public/msg',['gotoUrl'=>'window.history.go(-1);','sec'=>3,'msg'=>'您没有指定的办事处，请联系管理员']);
            }
        }
        $searchModel = new SystemDeviceFrameSearch();
        $arr = Yii::$app->request->queryParams;
        $searchModel->office_id = $kuid;
        $asArr = $searchModel->search($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $asArr_2 = $searchModel->search($arr,1);
            //获取总条数
            $DataCount = $searchModel->search($arr,2)->count();
            //取得最大页数
            $count=ceil($DataCount/3000);
            $j=0;
            //表头
            $title=['序号','硬件编号','厂家名称','办事处','仓库','规格','材质','品质','NFC','入库时间','入库负责人','出库时间','出库负责人','设备领取人','是否出库','批次','备注'];
            for($i=1;$i<=$count;$i++){
                $arrexport = Yii::$app->request->queryParams;
                $searchModel->offset=$j;
                $searchModel->limit=3000;
                $data=$searchModel->search($arrexport,1);
                $j=$i*3000;
                //处理csv要导出的数据
                $Csv=SystemDeviceFrame::CsvData($data,$asArr_2);
                $file_name="Led_".time().'_'.$i.".csv";
                //生成CSV文件并返回文件路径
                $filenameArr[]=ToolsClass::Getcsvzip($Csv,$title,$file_name);
            }
            //进行多个文件压缩并下载zip文件
            ToolsClass::zip($filenameArr,'Ledcsv.zip');
        }

        return $this->render('offices', [
            'searchModel' => $searchModel,
            'asArr'=>$asArr,
            'kuid'=>$kuid,
            'isoutput'=>SystemDeviceFrame::isoutput($kuid)
        ]);
    }

    /**
     * Displays a single SystemDeviceFrame model.
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

    /**
     * Creates a new SystemDeviceFrame model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    //添加设备
    public function actionCreate($kuid)
    {
        $model = new SystemDeviceFrame();
        if ($model->load(Yii::$app->request->post())) {
            if($model->saveDevice()){
                return $this->redirect(['offices','kuid'=>$kuid]);
            }else{
                return $this->checkSaveResult(false,Url::to(['create']));
            }
        }
        return $this->render('create', [
            'model' => $model,
            'kuid' => $kuid,
        ]);
    }
    //调仓设备
    public function actionChangeCreate($kuid)
    {
        $model = new SystemDeviceFrame();
        if ($model->load(Yii::$app->request->post())) {
            if($model->changeDevice()){
                return $this->redirect(['offices','kuid'=>$kuid]);
            }else{
                return $this->checkSaveResult(false,Url::to(['change_create']));
            }
        }
        return $this->render('change-create', [
            'model' => $model,
            'kuid' => $kuid,
        ]);
    }

    //调仓状态确认
    public function actionCheckChange(){
        $number = Yii::$app->request->post('number');
        $kuid = Yii::$app->request->post('kuid');
        if(!empty($number) && !empty($kuid)){
            $isExist = SystemDeviceFrame::findOne(['device_number' => $number,'receive_office_id'=>$kuid,'is_output'=>1]);
            if($isExist !== null){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Updates an existing SystemDeviceFrame model.
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
     * Deletes an existing SystemDeviceFrame model.
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
     * Finds the SystemDeviceFrame model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemDeviceFrame the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemDeviceFrame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *入库检测设备号的唯一性
     */
    public function actionCheckUnique(){
        $number = Yii::$app->request->post('number');
        if($number){
            $isExist = SystemDeviceFrame::findOne(['device_number' => $number]);
            if($isExist !== null){
                return false;
            }
            return true;
        }
        return true;
    }

    //查看修改某个设备
    public function actionEquipment($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            echo '<script type="text/javascript">'."\n";
            echo 'var pg = parent.layer.getFrameIndex(window.name)'."\n";
            echo 'parent.layer.close(pg)'."\n";
            echo 'parent.window.location.reload()'."\n";
            echo '</script>'."\n";
        }
        return $this->renderPartial('equipment', [
            'model' => $model,
        ]);
    }

    //点击查看屏幕详细信息
    public function actionScreenInfo($did,$device_number){
        $shop = Screen::find()->where(['number'=>$did])->select('shop_id')->asArray()->one();
        $shopModel = Shop::findOne(['id'=>$shop['shop_id']]);
        //物流信息
        $logistics = ShopLogistics::find()->where(['shop_id'=>$shop])->select('name,logistics_id')->orderBy('id desc')->one();
        $logisticsList = SystemDeviceFrame::getWlInfo($logistics['name'], $logistics['logistics_id']);
        $logs = LogDevice::find()->where(['device_number'=>$device_number])->asArray()->all();
        return $this->renderPartial('screen-info',[
            'model' => $shopModel?$shopModel:'',
            'logs'=>$logs?$logs:'',
            'logisticsList' => json_decode($logisticsList,true),
        ]);
    }

    //单个出库选择出库人页面
    public function actionSingle($deviceid,$kuid){
        $searchModel = new MemberSearch();
        $searchModel->inside = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('single', [
            'model' => $searchModel,
            'deviceid'=>$deviceid,
            'kuid'=>$kuid,
            'dataProvider' => $dataProvider,
        ]);
    }

    //单个出库执行
    public function actionOutPut(){
        $data=Yii::$app->request->post();
        $model = SystemDeviceFrame::findOne(['id'=>$data['deviceid']]);
        if($model && $model->getAttribute('is_output') !== 1){
            $model->is_output = 1;
            $model->stock_out_at = date('Y-m-d : H:i:s');
            $model->out_manager = Yii::$app->user->identity->getId();
            $model->receive_member_id = $data['memberid'];
            $re = $model->save();
            if($re){
                $member = member::find()->where(['id'=>$data['memberid']])->asArray()->one();
                LogDevice::addlog($model->device_number,$member,1,2);
            }
            return $re == true ? 1 : 0;
        }
        return 0;
    }

    //批量出库--给个人页面
    public function actionBatchs($kuid){
        $nmember = Member::find()->where(['inside'=>1])->select('id,name,mobile')->asArray()->all();
        foreach($nmember as $km =>$vm){
            $data[] = [
                'value'=>$vm['name'].','.$vm['mobile']
            ];
        }
        $model = new SystemDeviceFrame();
        return $this->renderPartial('batchs', [
            'model' => $model,
            'kuid' => $kuid,
            'nmember' => json_encode($data),
        ]);
    }
    //批量出库--调仓页面
    public function actionBatchsOffices($kuid){
        $nmember = Member::find()->where(['inside'=>1])->select('id,name,mobile')->asArray()->all();
        foreach($nmember as $km =>$vm){
            $data[] = [
                'value'=>$vm['name'].','.$vm['mobile']
            ];
        }
        $model = new SystemDeviceFrame();
        return $this->renderPartial('batchs-offices', [
            'model' => $model,
            'kuid' => $kuid,
            'nmember' => json_encode($data),
        ]);
    }

    //检验设备状况
    public function actionCheckInfo(){
        $array = yii::$app->request->post();
        $numarray['emptynum'] = [];
        $numinfo = SystemDeviceFrame::find()->where(['and',['in','device_number',$array['deviceid']],['office_id'=>$array['kuid']]])->select('id,device_number,is_output')->asArray()->all();
        $numid = array_column($numinfo,'device_number');
        foreach($array['deviceid'] as $ks=>$vs){
            if(in_array($vs,$numid)){

            }else{
                $numarray['emptynum'][]=$vs;
            }
        }
        $numarray['outputnum'] = [];
        foreach($numinfo as $key=>$value){
            if($value['is_output'] == 1 ){
                $numarray['outputnum'][] = $value['device_number'];
            }
        }

        if(empty($numarray['emptynum']) && empty($numarray['outputnum'])){
            return 1;
        }
        return json_encode($numarray);
    }

    //批量出库ajax
    public function actionLot(){
        try{
            $this->enableCsrfValidation = false;
            $ids = $number = Yii::$app->request->post();
            $member = explode(',',$ids['member']);
            $memberid = Member::find()->where(['mobile'=>$member[1]])->asArray()->one();
            if(count($ids['deviceid']) > 0){
                SystemDeviceFrame::updateAll(['is_output' => 1,'stock_out_at'=>date('Y-m-d : H:i:s'),'out_manager'=>Yii::$app->user->identity->getId(),'receive_member_id'=>$memberid['id']],['device_number' => $ids['deviceid']]);
                foreach($ids['deviceid'] as $knum=>$vnum){
                    LogDevice::addlog($vnum,$memberid,1,2);
                }
                return 1;
            }
            throw new Exception("[error]没有出库设备ID失败",'error');
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            return 0;
        }
    }

    //批量调仓ajax
    public function actionLotku(){
        try{
            $this->enableCsrfValidation = false;
            $ids = $number = Yii::$app->request->post();
            $offices = SystemOffice::find()->where(['id'=>$ids['tokuid']])->asArray()->one();
            if(count($ids['deviceid']) > 0){
                SystemDeviceFrame::updateAll(['is_output' => 1,'stock_out_at'=>date('Y-m-d : H:i:s'),'out_manager'=>Yii::$app->user->identity->getId(),'receive_office_id'=>$ids['tokuid']],['device_number' => $ids['deviceid']]);
                foreach($ids['deviceid'] as $knum=>$vnum){
                    LogDevice::addlog($vnum,$offices,2,2);
                }
                return 1;
            }
            throw new Exception("[error]没有出库设备ID失败");
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            return 0;
        }
    }
}
