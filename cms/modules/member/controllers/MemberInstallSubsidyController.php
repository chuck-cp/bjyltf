<?php

namespace cms\modules\member\controllers;

use cms\models\LogAccount;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\MemberInstallSubsidy;
use cms\modules\member\models\search\MemberInstallSubsidySearch;
use cms\modules\member\models\MemberInstallSubsidyList;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * MemberInstallSubsidyController implements the CRUD actions for MemberInstallSubsidy model.
 */
class MemberInstallSubsidyController extends CmsController
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
     * Lists all MemberInstallSubsidy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberInstallSubsidySearch();
        $searchModel->type=1;
        $map=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($map);
        if(isset($map['search']) && $map['search'] == 0){
            $DataAll = $searchModel->search($map,1)->asArray()->all();
            if(empty($DataAll)){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','安装人电话','安装人姓名','常驻地址','本日安装店铺','本日安装屏幕','安装人本日安装收入（元）','本日指派店铺','本日指派屏幕','本日补贴额'];
            $csv=[];
            foreach ($DataAll as $k=>$v){
                $csv[$k]['id']=$v['id'];
                $csv[$k]['mobile']=$v['memberNameMobile']['mobile'];
                $csv[$k]['name']=$v['memberNameMobile']['name'];
                $csv[$k]['live_area_name']=$v['memberArea']['live_area_name'];
                $csv[$k]['install_shop_number']=$v['install_shop_number'];
                $csv[$k]['install_screen_number']=$v['install_screen_number'];
                $csv[$k]['income_price']=ToolsClass::priceConvert($v['income_price']);
                $csv[$k]['assign_shop_number']=$v['assign_shop_number'];
                $csv[$k]['assign_screen_number']=$v['assign_screen_number'];
                $csv[$k]['subsidy_price']=ToolsClass::priceConvert($v['subsidy_price']);
            }
            $file_name="安装人补贴".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MemberInstallSubsidy model.
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
     * Creates a new MemberInstallSubsidy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberInstallSubsidy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemberInstallSubsidy model.
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
     * Deletes an existing MemberInstallSubsidy model.
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
     * 确认金额无误
     */
    public function actionAmountofsubsidies(){
        $DataAll=Yii::$app->request->post();
        if(!$DataAll['id']){
            return json_encode(['code'=>2,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //安装人每日安装数量统计及补贴金额统计表 修改今日补贴金额
            MemberInstallSubsidy::subsidyprice($DataAll['subsidy_price']*100,$DataAll['id']);
            //新增补贴记录
            MemberInstallSubsidyList::AddingRecord($DataAll['subsidy_price']*100,$DataAll['id'],$DataAll['member_id'],$DataAll['subisdy_desc']);
            //用户账户流水信息
            if(!is_int((int)($DataAll['subsidy_price']*100))){
                return json_encode(['code'=>5,'msg'=>'小数点后最多为两位']);
            }
            LogAccount::writeLog(4,(int)($DataAll['subsidy_price']*100),1,'安装人补贴费用',$DataAll['member_id']);
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'补贴费用成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return json_encode(['code'=>3,'msg'=>'补贴费用失败']);
        }
    }

    /**
     * Finds the MemberInstallSubsidy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberInstallSubsidy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberInstallSubsidy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
