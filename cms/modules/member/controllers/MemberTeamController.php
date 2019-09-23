<?php

namespace cms\modules\member\controllers;

use cms\modules\shop\models\Shop;
use Yii;
use cms\modules\member\models\MemberTeam;
use cms\modules\member\models\search\MemberTeamSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2tech\csvgrid\CsvGrid;
/**
 * MemberTeamController implements the CRUD actions for MemberTeam model.
 */
class MemberTeamController extends Controller
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
     * Lists all MemberTeam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberTeamSearch();
        $arr = Yii::$app->request->queryParams;
        //$asArr = $searchModel->search($arr);
        $dataProvider = $searchModel->search($arr);
        if(isset($arr['search']) && $arr['search'] == 0) {
            $teamDataObj = $searchModel->search($arr, 1);
            $exporter = new CsvGrid([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $teamDataObj,
                    'pagination' => [
                        'pageSize' => 100, // export batch size
                    ],
                ]),
            ]);
            if($exporter->dataProvider->getCount() == 0){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $exporter->export()->send(chr(0xEF).chr(0xBB).chr(0xBF).'team_'.date('Y-m-d').'_'.time().'.csv');
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MemberTeam model.
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
     * Creates a new MemberTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new MemberTeam();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->member_id]);
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Updates an existing MemberTeam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->member_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    /**
     * Finds the MemberTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MemberTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberTeam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /*
     * 解除团队订单
     */
    public function actionDissolve()
    {
        $team_member_id = Yii::$app->request->post('team_member_id');
        if(!Shop::find()->where(['install_team_id'=>$team_member_id])->andWhere(['in','status',[0,1,2]])->count()){
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Shop::updateAll(['install_team_id'=>0,'install_member_id'=>0,'install_member_name'=>''],['install_team_id'=>$team_member_id]);
            MemberTeam::updateAll(['not_install_shop_number'=>0,'not_assign_shop_number'=>0],['id'=>$team_member_id]);
            $transaction->commit();
            return true;
        }catch (Exception $e){
            Yii::error($e->getMessage(),'db');
            $transaction->rollBack();
            return false;
        }
    }
}
