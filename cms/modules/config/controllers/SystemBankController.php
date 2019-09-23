<?php

namespace cms\modules\config\controllers;

use cms\modules\member\models\MemberBank;
use common\libs\ToolsClass;
use Yii;
use cms\modules\config\models\SystemBank;
use cms\modules\config\models\search\SystemBankSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * SystemBankController implements the CRUD actions for SystemBank model.
 */
class SystemBankController extends CmsController
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
     * Lists all SystemBank models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemBankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemBank model.
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
     * 判断银行是否存在
     */
    public function actionExist(){
        if(Yii::$app->request->isAjax){
            $model = new SystemBank();
            $bank_name=Yii::$app->request->post('bank_name');
            $BankCount=$model->BankCount($bank_name);
            if($BankCount>=1)
                return 2;
            return 1;
        }
    }


    /**
     * Creates a new SystemBank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemBank();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SystemBank model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SystemBank model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        MemberBank::updateAll(['status'=>2],['bank_id'=>$id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemBank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemBank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemBank::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
