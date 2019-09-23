<?php

namespace cms\modules\withdraw\controllers;

use cms\models\LogExamine;
use cms\models\User;
use common\libs\ToolsClass;
use Yii;
use cms\modules\withdraw\models\MemberWithdraw;
use cms\modules\withdraw\models\search\MemberWithdrawSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\modules\member\models\MemberInfo;
use common\libs\CsvClass;
/**
 * MemberWithdrawController implements the CRUD actions for MemberWithdraw model.
 */
class MemberWithdrawController extends CmsController
{
    public $enableCsrfValidation = false;
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
     * Lists all MemberWithdraw models.
     * @return mixed
     * 等待财务
     */
    public function actionIndex()
    {
        $searchModel = new MemberWithdrawSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['search']) && $arr['search']=='0'){
            $DataAll = $searchModel->search($arr,1,0)->asArray()->all();
            if(empty($DataAll)){
                $dataProvider = $searchModel->search($arr,1,1);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                $title=['序号','流水号','申请时间','提现人ID','姓名','手机号','收款人银行','收款人','身份证','账户类型','收款账号','银行预留手机号','提现状态','提现金额','手续费','账户余额','审核状态'];
                foreach($DataAll as $k=>$v){
                    $csv[$k]['id']=$v['id'];
                    $csv[$k]['serial_number']=$v['serial_number']."\t";
                    $csv[$k]['create_at']=$v['create_at'];
                    $csv[$k]['member_id']=$v['member_id'];
                    $csv[$k]['member_name']=$v['member_name'];
                    $csv[$k]['mobile']=$v['mobile']."\t";
                    $csv[$k]['bank_name']=$v['bank_name'];
                    $csv[$k]['payee_name']=$v['payee_name'];
                    $csv[$k]['id_number']=MemberInfo::getIdInfoByMemberId($v['member_id'],'id_number').",";
                    $csv[$k]['account_type']=$v['account_type']==1?'个人':'公司';
                    $csv[$k]['bank_account']=$v['bank_account'].",";
                    $csv[$k]['bank_mobile']=$v['bank_mobile']."\t";
                    $csv[$k]['status']=$v['status']==0?'未提现':'待提现';
                    $csv[$k]['price']=number_format($v['price']/100,2);
                    $csv[$k]['poundage']=number_format($v['poundage']/100,2);
                    $csv[$k]['account_balance']=number_format($v['account_balance']/100,2);
                    $csv[$k]['examine_status']=MemberWithdraw::getExaimneStatus($v['examine_status'],$v['examine_result']);
                }
                $file_name="withdraw".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($csv,$title,$file_name);
            }
        }
        //$searchModel->examine_status = 0;
        $dataProvider = $searchModel->search($arr,1,1);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 审核或者驳回(财务和出纳阶段)
     */
    public function actionExamine(){
        $id = Yii::$app->request->post('id');
        $model = MemberWithdraw::findOne(['id'=>$id]);
        if($model){
            $user_id = Yii::$app->user->identity->getId();
           // $user_type = User::find()->where(['id'=>4])->select('type')->asArray()->one();
            $type = Yii::$app->request->post('type');
            $page = Yii::$app->request->post('page');
            switch ($page){
                case 'index':
                    $judge = 1;
                    break;
                case 'audit':
                    $judge = 2;
                    break;
            }
            $transaction = Yii::$app->db->beginTransaction();
            $withModel = new MemberWithdraw();
                /*if($user_type['type'] == '0'){//判断操作用户是谁*/
                if($model->examine_status == $judge){
                    return 5;
                }
                switch ($type){
                    case 'pass':
                        $re = $withModel->saveWithdraw($id,3,$judge,2);
                        break;
                    case 'rebut':
                        $content = Yii::$app->request->post('content');
                        $re = $withModel->saveWithdraw($id,3,$judge,1,$content);
                        break;
                }
                if($re){
                    $transaction->commit();
                    return 1;
                }else{
                    $transaction->rollBack();
                    return 0;
                }
                /*}else{
                    throw new \Exception('您无权操作！');
                }*/
        }else{
            throw new \Exception('您要操作的数据有误！');
        }
    }
    /**
     * 财务驳回页面
     */
    public function actionRebut($id){
        $model = new LogExamine();
        $model->id = $id;
        return $this->renderPartial('rebut',[
            'model' => $model,
        ]);
    }


    /**
     * 批量审核通过 财务 审计
     */
    public function actionBatchExamine(){
        $data=Yii::$app->request->post();
        switch ($data['page']){
            case 'index':
                $judge = 1;
                break;
            case 'audit':
                $judge = 2;
                break;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $withModel = new MemberWithdraw();
            foreach (explode(',',trim($data['ids'],',')) as $id){
                $model = MemberWithdraw::findOne(['id'=>$id]);
                if($model->examine_status == $judge){
                   continue;
                }
                $withModel->saveWithdraw($id,3,$judge,2);
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'审核成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'审核失败']);
        }
    }

    /**
     * 等待审计
     */
    public function actionAudit(){
        $searchModel = new MemberWithdrawSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['search']) && $arr['search']=='0'){
            $DataAll = $searchModel->search($arr,2,0)->asArray()->all();
            if(empty($DataAll)){
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams,3,1);
                return $this->render('cashier', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                $title=['序号','流水号','申请时间','提现人ID','姓名','手机号','收款人银行','收款人','身份证','账户类型','收款账号','银行预留手机号','提现状态','提现金额','手续费','账户余额','审核状态'];
                foreach($DataAll as $k=>$v){
                    $csv[$k]['id']=$v['id'];
                    $csv[$k]['serial_number']=$v['serial_number']."\t";
                    $csv[$k]['create_at']=$v['create_at'];
                    $csv[$k]['member_id']=$v['member_id'];
                    $csv[$k]['member_name']=$v['member_name'];
                    $csv[$k]['mobile']=$v['mobile']."\t";
                    $csv[$k]['bank_name']=$v['bank_name'];
                    $csv[$k]['payee_name']=$v['payee_name'];
                    $csv[$k]['id_number']=MemberInfo::getIdInfoByMemberId($v['member_id'],'id_number').",";
                    $csv[$k]['account_type']=$v['account_type']==1?'个人':'公司';
                    $csv[$k]['bank_account']=$v['bank_account'].",";
                    $csv[$k]['bank_mobile']=$v['bank_mobile']."\t";
                    $csv[$k]['status']=$v['status']==0?'未提现':'待提现';
                    $csv[$k]['price']=number_format($v['price']/100,2);
                    $csv[$k]['poundage']=number_format($v['poundage']/100,2);
                    $csv[$k]['account_balance']=number_format($v['account_balance']/100,2);
                    $csv[$k]['examine_status']=MemberWithdraw::getExaimneStatus($v['examine_status'],$v['examine_result']);
                }
                $file_name="withdraw".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($csv,$title,$file_name);
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,2,1);
        return $this->render('audit', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 等待出纳
     */
    public function actionCashier(){
        $searchModel = new MemberWithdrawSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['search']) && $arr['search']=='0'){
            $DataAll = $searchModel->search($arr,3,0)->asArray()->all();
            if(empty($DataAll)){
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams,3,1);
                return $this->render('cashier', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                $title=['序号','流水号','申请时间','提现人ID','姓名','手机号','收款人银行','收款人','身份证','账户类型','收款账号','银行预留手机号','提现状态','提现金额','手续费','账户余额','审核状态'];
                foreach($DataAll as $k=>$v){
                    $csv[$k]['id']=$v['id'];
                    $csv[$k]['serial_number']=$v['serial_number']."\t";
                    $csv[$k]['create_at']=$v['create_at'];
                    $csv[$k]['member_id']=$v['member_id'];
                    $csv[$k]['member_name']=$v['member_name'];
                    $csv[$k]['mobile']=$v['mobile']."\t";
                    $csv[$k]['bank_name']=$v['bank_name'];
                    $csv[$k]['payee_name']=$v['payee_name'];
                    $csv[$k]['id_number']=MemberInfo::getIdInfoByMemberId($v['member_id'],'id_number').",";
                    $csv[$k]['account_type']=$v['account_type']==1?'个人':'公司';
                    $csv[$k]['bank_account']=$v['bank_account'].",";
                    $csv[$k]['bank_mobile']=$v['bank_mobile']."\t";
                    $csv[$k]['status']=$v['status']==0?'未提现':'待提现';
                    $csv[$k]['price']=number_format($v['price']/100,2);
                    $csv[$k]['poundage']=number_format($v['poundage']/100,2);
                    $csv[$k]['account_balance']=number_format($v['account_balance']/100,2);
                    $csv[$k]['examine_status']=MemberWithdraw::getExaimneStatus($v['examine_status'],$v['examine_result']);
                }
                $file_name="withdraw".date("mdHis",time()).".csv";
                ToolsClass::Getcsv($csv,$title,$file_name);
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,3,1);
        return $this->render('cashier', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 提现记录
     */
    public function actionWithdraw(){
        $searchModel = new MemberWithdrawSearch();
        $arr = Yii::$app->request->queryParams;
        if(isset($arr['search']) && $arr['search']=='0'){
            ini_set("memory_limit","2048M");
            $file_name="withdraw".date("mdHis",time()).".csv";
            $DataCount = $searchModel->search($arr,4,0)->count();
            if($DataCount==0){
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams,4,1);
                return $this->render('withdraw', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
           $title=['序号','流水号','申请时间','提现人ID','姓名','手机号','收款人银行','收款人','身份证','账户类型','收款账号','银行预留手机号	','提现状态','提现金额','手续费','账户余额'];
            $count=ceil($DataCount/1000);
            $j=0;
           // echo "Initial: ".memory_get_usage()." bytes \n";
            for($i=1;$i<=$count;$i++){
                $searchModel->offset=$j;
                $searchModel->limit=1000;
                $j=$i*1000;
                $data=$searchModel->search($arr,4,2);
                //处理csv要导出的数据
                $CsvData = CsvClass::MemberWithdrawWithdrawData($data);
                if($i==1){
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name);
                }else{
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name,false);
                }
                unset($CsvData);
            }
        /*    echo "Final: ".memory_get_usage()." bytes \n";
            echo "Peak: ".memory_get_peak_usage()." bytes \n";*/
            CsvClass::CsvDownload($file_name);
        }
        $dataProvider = $searchModel->search($arr,4,1);
       // $dataProvider = $searchModel->search($arr,4,0)->asArray()->all();
        return $this->render('withdraw', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 提现成功或者失败
     */
    public function actionCashExamine(){
        $id = Yii::$app->request->post('id');
        $model = MemberWithdraw::findOne(['id'=>$id]);
        if($model){
            $type = Yii::$app->request->post('type');
            $page = Yii::$app->request->post('page');
            $judge = 3;
            $transaction = Yii::$app->db->beginTransaction();
            $withModel = new MemberWithdraw();
            if($model->examine_status == $judge){
                return 5;
            }
            switch ($type){
                case 'pass':
                    $re = $withModel->saveCashier($id,3,$judge,2);
                    break;
                case 'rebut':
                    //$content = Yii::$app->request->post('content');
                    $re = $withModel->saveCashier($id,3,$judge,1);
                    break;
            }
            if($re){
                $transaction->commit();
                return 1;
            }else{
                $transaction->rollBack();
                return 0;
            }
        }else{
            return 0;
        }
    }

    /**
     * 批量提成成功
     *
     */
    public function actionBatchCashExamine(){
        $data=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $withModel = new MemberWithdraw();
            $judge = 3;
            foreach (explode(',',trim($data['ids'],',')) as $id){
                $model = MemberWithdraw::findOne(['id'=>$id]);
                if($model->examine_status == $judge){
                    continue;
                }
                $withModel->saveCashier($id,3,$judge,2);
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'提现成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    //查看详情
    public function actionDetail($id)
    {
        $mwlist = MemberWithdraw::findOne(['id'=>$id]);
        return $this->renderPartial('detail', [
            'mwlist' => $mwlist,
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>3])->orderBy('create_at desc')->asArray()->all(),
        ]);
    }
    /**
     * Displays a single MemberWithdraw model.
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
     * Creates a new MemberWithdraw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberWithdraw();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemberWithdraw model.
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
     * Deletes an existing MemberWithdraw model.
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
     * Finds the MemberWithdraw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberWithdraw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberWithdraw::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
