<?php

namespace cms\modules\account\controllers;

use Yii;
use cms\modules\account\models\MemberOrderReward;
use cms\modules\account\models\search\MemberOrderRewardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\libs\ToolsClass;
/**
 * MemberOrderRewardController implements the CRUD actions for MemberOrderReward model.
 */
class MemberOrderRewardController extends Controller
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
     * Lists all MemberOrderReward models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new MemberOrderRewardSearch();
        $map=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($map,0);
        //奖励金总额
        $TotalPrice= ToolsClass::priceConvert(MemberOrderReward::find()->sum('reward_price'));
        if(isset($map['search']) && $map['search'] == 0){
            $DateArr = $searchModel->search($map,1)->asArray()->all();
            if(empty($DateArr)){
                return $this->render('index', [
                    'TotalPrice'=>$TotalPrice,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['序号','订单编号','生成时间','商家编号','商家名称','所属地区','法人姓名','法人手机号','交易费用','奖励费用'];
            $CsvArr=MemberOrderReward::ExportCsv($DateArr);
            $file_name="MemberOrderReward".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($CsvArr,$title,$file_name);
        }
        return $this->render('index', [
            'TotalPrice'=>$TotalPrice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MemberOrderReward model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MemberOrderReward model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberOrderReward();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemberOrderReward model.
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
     * Deletes an existing MemberOrderReward model.
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
     * Finds the MemberOrderReward model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberOrderReward the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberOrderReward::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
