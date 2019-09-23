<?php

namespace cms\modules\config\controllers;

use common\libs\ToolsClass;
use function GuzzleHttp\Psr7\str;
use Yii;
use cms\modules\config\models\SystemVersion;
use cms\modules\config\models\search\SystemVersionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * SystemVersionController implements the CRUD actions for SystemVersion model.
 */
class SystemVersionController extends CmsController
{
    //public $enableCsrfValidation = false;
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
     * Lists all SystemVersion models.
     * @return mixed
     */
    public function actionVersion()
    {
        $searchModel = new SystemVersionSearch();
        $searchModel->app_type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('version', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIos()
    {
        $searchModel = new SystemVersionSearch();
        $searchModel->app_type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('ios', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPid()
    {
        $searchModel = new SystemVersionSearch();
        $searchModel->app_type = 3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('pid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single SystemVersion model.
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
     * Creates a new SystemVersion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_type)
    {
        $model = new SystemVersion();
        $model->create_user = Yii::$app->user->identity->username;
        $prr = Yii::$app->request->post();
        if ($model->load($prr) && $model->save()) {
            if($prr['SystemVersion']['app_type'] ==2){
                return $this->redirect(['/config/system-version/ios']);
            }elseif($prr['SystemVersion']['app_type'] ==1){
                return $this->redirect(['/config/system-version/version']);
            }elseif($prr['SystemVersion']['app_type'] ==3){
                return $this->redirect(['/config/system-version/pid']);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'app_type' => $app_type,
        ]);
    }
    /**
     * Updates an existing SystemVersion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            echo "<script>parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
        }

//        return $this->render('update', [
//            'model' => $model,
//        ]);
    }
    public function actionDetail($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['version', 'id' => $model->id]);
            echo "<script>parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
        }

        return $this->renderPartial('detail', [
            'model' => $model,
        ]);
    }
    /**
     * 停用版本
     */
    public function actionStop(){
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if($model){
            $model->status = 0;
            $re = $model->save();
            return $re;
        }else{
            return false;
        }

    }
    /**
     * Deletes an existing SystemVersion model.
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
     * Finds the SystemVersion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemVersion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemVersion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
