<?php
namespace cms\modules\shop\controllers;

use common\libs\ToolsClass;
use Yii;
use cms\modules\shop\models\BuildingShopFloor;
use cms\modules\shop\models\search\BuildingShopFloorSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\modules\shop\models\BuildingShopPosition;
/**
 * BuildingShopFloorController implements the CRUD actions for BuildingShopFloor model.
 */
class BuildingShopFloorController extends CmsController
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
     * Lists all BuildingShopFloor models.
     * Led显示类表
     * @return mixed
     */
    public function actionLedIndex()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('led-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all BuildingShopFloor models.
     * 画框显示类表
     * @return mixed
     */
    public function actionPosterIndex()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('poster-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BuildingShopFloor model.
     * @param string $id,$type
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$type)
    {
        if($type ==1){
            return $this->render('led-view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->render('poster-view', [
                'model' => $this->findModel($id),
            ]);
        }

    }


    public function actionInstallLedView($id){
        //大堂等候区的数据
        $Data =  BuildingShopPosition::getFloorLedView($id,1);
        return $this->render('install-led-view', [
            'data' =>$Data
        ]);
    }

    public function actionInstallPosterView($id,$position_id=0){
        $label= BuildingShopPosition::getPostaionLable($id,2);
        if($position_id == 0){
            $position_id = $label[0]['position_id'];
        }
        $Data =  BuildingShopPosition::getFloorPosterView($position_id);
        return $this->render('install-poster-view', [
            'data'=>$Data,
            'id'=>$id,
            'position_id'=>$position_id,
            'label'=>$label,
        ]);
    }


    /**
     * Creates a new BuildingShopFloor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildingShopFloor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BuildingShopFloor model.
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
     * Deletes an existing BuildingShopFloor model.
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
     * Finds the BuildingShopFloor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BuildingShopFloor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BuildingShopFloor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
