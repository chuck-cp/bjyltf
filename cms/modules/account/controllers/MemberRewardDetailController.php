<?php

namespace cms\modules\account\controllers;

use common\libs\ToolsClass;
use Yii;
use cms\modules\account\models\MemberRewardDetail;
use cms\modules\account\models\search\MemberRewardDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MemberRewardDetailController implements the CRUD actions for MemberRewardDetail model.
 */
class MemberRewardDetailController extends Controller
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
     * Lists all MemberRewardDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$url = 'http://10.240.0.39/ws/v1/orderServiceWs/queryOrderVo?param=';
        $param = array('dataSourceId'=>'zh_CN','orderSerialNumber'=>'A12201804111023117118070');
        $aa = ToolsClass::b2bcurl($url,$param,'POST',1);

        $vv=\Yii::$app->des3->decode(json_encode($aa));
        var_dump($aa);
        die;*/
       /* $searchModel = new MemberRewardDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/



        $searchModel = new MemberRewardDetailSearch();
        $map=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($map,0);
        //奖励金总额
        $TotalPrice= ToolsClass::priceConvert(MemberRewardDetail::find()->sum('reward_price'));
        if(isset($map['search']) && $map['search'] == 0){
            $DateArr = $searchModel->search($map,1)->asArray()->all();
            if(empty($DateArr)){
                return $this->render('index', [
                    'TotalPrice'=>$TotalPrice,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','订单编号','生成时间','完成时间','商家编号','商家名称','总部名称','所属地区','法人ID','法人姓名','法人手机号','交易费用','奖励费用','屏幕编号'];
            $CsvArr=MemberRewardDetail::ExportCsv($DateArr);
            $file_name="广告销售奖励支出".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($CsvArr,$title,$file_name);
        }
        return $this->render('index', [
            'TotalPrice'=>$TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MemberRewardDetail model.
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
     * Creates a new MemberRewardDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberRewardDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemberRewardDetail model.
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
     * Deletes an existing MemberRewardDetail model.
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
     * Finds the MemberRewardDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberRewardDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberRewardDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
