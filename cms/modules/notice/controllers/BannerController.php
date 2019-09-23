<?php

namespace cms\modules\notice\controllers;

use Yii;
use cms\modules\notice\models\SystemBanner;
use cms\modules\notice\models\search\BannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * BannerController implements the CRUD actions for SystemBanner model.
 */
class BannerController extends CmsController
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
     * Lists all SystemBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch();
        $type = Yii::$app->request->get('type') ? Yii::$app->request->get('type') : 1;
        $searchModel->type = $type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => $type,
        ]);
    }

    /**
     * Displays a single SystemBanner model.
     * @param integer $id
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
     * banner管理 , 'id' => $model->id
     */
    public function actionDetail($id=1){
        $id = 1;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && SystemBanner::infoSave($model)) {
            return $this->redirect(['index']);
        } else {
            return $this->render('detail', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Creates a new SystemBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemBanner();
        $prr = Yii::$app->request->post();
        //判断banner数量是否超过6个
        if(Yii::$app->request->isPost){
            $judge = $model->judgeBannerNumbers($prr['SystemBanner']['type']);
            if($judge){
                return $this->checkSaveResult(['result'=>false, 'message'=>'该banner图最多添加6张，请删除后再添加！'],'index.php?r=notice%2Fbanner%2Findex&type='.$prr['SystemBanner']['type']);
            }
        }
        if ($model->load($prr) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SystemBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SystemBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 排序
     */
    public function actionSort(){
        $data=Yii::$app->request->post();
        //ToolsClass::p($data);die;
        foreach ($data['sort'] as $k => $v) {
            SystemBanner::updateAll(['sort'=>$v],['id'=>$k]);
        }
        return json_encode(['code'=>'1','msg'=>'排序成功']);
    }
    /**
     * Finds the SystemBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemBanner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
