<?php

namespace cms\modules\systemstartup\controllers;

use common\libs\ToolsClass;
use Yii;
use cms\modules\systemstartup\models\SystemStartup;
use cms\modules\systemstartup\models\search\SystemStartupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * SystemStartupController implements the CRUD actions for SystemStartup model.
 */
class SystemStartupController extends CmsController
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
     * Lists all SystemStartup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemStartupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemStartup model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SystemStartup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemStartup();
        $prr = Yii::$app->request->post();
        if(isset($prr['SystemStartup']['start_pic'][0])){
            $fliter_prr = array_filter($prr['SystemStartup']['start_pic']);
            //多图
            if(!empty($fliter_prr)){
                $model->start_pic = json_encode($fliter_prr,true);
                unset($prr['SystemStartup']['start_pic']);
            }
        }
        //单图
        if(isset($prr['SystemStartup']['single_pic']) && $prr['SystemStartup']['single_pic']){
            $model->start_pic = $prr['SystemStartup']['single_pic'];
            unset($prr['SystemStartup']['single_pic']);
        }
        $model->create_user_id = Yii::$app->user->identity->id;
        $model->create_user_name = Yii::$app->user->identity->username;
        if ($model->load($prr) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SystemStartup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost){
            $prr = Yii::$app->request->post();

            if(isset($prr['SystemStartup']['start_pic'][0])){//多图
                $fliter_prr = array_filter($prr['SystemStartup']['start_pic']);
                if(!empty($fliter_prr)){
                    $model->start_pic = json_encode($fliter_prr,true);
                    $prr['SystemStartup']['start_pic']= json_encode($fliter_prr,true);
                }
            }else{//单图
                $model->start_pic = $prr['SystemStartup']['single_pic'];
                $prr['SystemStartup']['start_pic'] = $prr['SystemStartup']['single_pic'];
                unset($prr['SystemStartup']['single_pic']);
            }

            $model->create_user_id = Yii::$app->user->identity->id;
            $model->create_user_name = Yii::$app->user->identity->username;
            if($model->link){
                $model->haslink = 1;
            }else{
                $model->haslink = 0;
            }
            if ($model->load($prr) && $model->save()) {
                return $this->redirect(['index']);
            }
        }else {
            if(is_string($model->start_pic)){
                if(substr($model->start_pic,0,4) == 'http'){
                    $model->single_pic = $model->start_pic;
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SystemStartup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemStartup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemStartup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemStartup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
