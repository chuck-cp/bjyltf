<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\examine\models\ActivityDetail;
use cms\modules\examine\models\ShopContract;
use cms\modules\examine\models\ShopHeadquartersList;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use Yii;
use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\examine\models\search\ShopHeadSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopHeadController implements the CRUD actions for ShopHeadquarters model.
 */
class ShopHeadController extends CmsController
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
     * Lists all ShopHeadquarters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopHeadquarters model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);//总部
        $arraylist = ShopHeadquartersList::getShopByListId($id);
        $pageSize =10;
        $pages = new Pagination(['totalCount' => count($arraylist),'pageSize' => $pageSize]);
        $arraylist = array_slice($arraylist,$pages->offset,$pages->limit);
        $lastpage = (int)count($arraylist);
        return $this->render('view', [
            'model' => $model,
            'arraylist' => $arraylist,
            'lastpage' => $lastpage,
            'pages' => $pages,
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>7])->orderBy('create_at desc')->asArray()->all()
        ]);
    }

    /**
     * Creates a new ShopHeadquarters model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopHeadquarters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopHeadquarters model.
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
     * Deletes an existing ShopHeadquarters model.
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
     * Finds the ShopHeadquarters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopHeadquarters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopHeadquarters::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 总部审核驳回
     */
    public function actionReject(){
        $DataArr = Yii::$app->request->post();
        if(!$DataArr['headid']){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $model = ShopHeadquarters::findOne(['id'=>$DataArr['headid']]);
        if($model) {
            //是否已经审核过
            if ($model->examine_status > 0) {
                return json_encode(['code' => 2, 'msg' => '该公司已审核，请勿重复审核']);
            }

            $LogExamine=new LogExamine();
            $LogExamine->examine_key=7;
            $LogExamine->foreign_id=$DataArr['headid'];
            $LogExamine->examine_result=2;
            $LogExamine->examine_desc=$DataArr['data'];
            $LogExamine->create_user_id=Yii::$app->user->identity->getId();
            $LogExamine->create_user_name=Yii::$app->user->identity->username;
            $LogExamine->create_at=date('Y-m-d H:i:s');
            $LogExamine->save();
            $model = ShopHeadquarters::findOne(['id' => $DataArr['headid']]);
            $model->examine_status = 2;
            if ($model->save()) {
                return json_encode(['code' => 1, 'msg' => '操作成功']);
            } else {
                return json_encode(['code' => 2, 'msg' => '操作失败']);
            }
        }
    }
    /**
     * 总部审核通过
     */
    public function actionExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['headid']){
            return false;
        }
        $model = ShopHeadquarters::findOne(['id'=>$arr['headid']]);
        if($model){
            //是否已经审核过
            if($model->examine_status > 0){
                return 5;
            }
            $roundnum = ToolsClass::str_rand(28-strlen($model->id));//随机子字符串
            $agr = $model->id.$roundnum.".pdf";//随机数协议

            //yl_log_examine审核日志
            $logModel = new LogExamine();
            $logModel->examine_key = 7;
            $logModel->foreign_id = $arr['headid'];
            $logModel->examine_result = 1;
            $logModel->create_user_id = Yii::$app->user->identity->getId();
            $logModel->create_user_name = Yii::$app->user->identity->username;
            $logModel->save();

            $model->agreement_name = $agr;//协议
            $model->examine_status = 1;

            //将安装活动状态改为3查看
            $activiModel = ActivityDetail::findOne(['id'=>$model->activity_detail_id]);
            if($activiModel){
                $activiModel->status = 3;
                $activiModel->shop_name = $model->company_name . '(' . $activiModel->shop_name . ')';
                $activiModel->save(false);
            }

            //添加合同审核
            $contract = new ShopContract();
            $contract->shop_id = $model->id;
            $contract->shop_type = 2;
            $contract->create_at = date('Y-m-d H:i:s',time());
            $contract->save(false);

            //协议所需数据写redis
            $redisObj = Yii::$app->redis;
            $redisObj->select(4);
            $xieyi = [
                'shop_id'=>$model->id,
                'agreement_name'=>$agr,
                'shop_type'=>2,//总部
            ];
            $redisObj->rpush('system_member_agreement_list',json_encode($xieyi));
            if($res = $model->save()){
                return $res;
            }else{
                return $res =0;
            }
        }else{
            return false;
        }

    }
}
