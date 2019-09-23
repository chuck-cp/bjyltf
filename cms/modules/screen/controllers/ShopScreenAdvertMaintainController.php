<?php

namespace cms\modules\screen\controllers;

use Yii;
use cms\modules\screen\models\ShopScreenAdvertMaintain;
use cms\modules\screen\models\search\ShopScreenAdvertMaintainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\examine\models\search\MemberInfoSearch;

/**
 * ShopScreenAdvertMaintainController implements the CRUD actions for ShopScreenAdvertMaintain model.
 */
class ShopScreenAdvertMaintainController extends Controller
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
     * Lists all ShopScreenAdvertMaintain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopScreenAdvertMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopScreenAdvertMaintain model.
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
     * Creates a new ShopScreenAdvertMaintain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopScreenAdvertMaintain();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopScreenAdvertMaintain model.
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
     * Deletes an existing ShopScreenAdvertMaintain model.
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
     * 电工列表
     * @param $id
     * @return string
     */
    public function actionElectrician($id){
        $searchModel = new MemberInfoSearch();
        $searchModel->electrician_status=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('electrician', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }

    /**
     * 指派电工
     * @return string
     */
    public function actionMaintainAssign()
    {
        $model = new ShopScreenAdvertMaintain();
        return $model->getMaintainAssign(Yii::$app->request->get());
    }

    /**
     * 取消指派
     * @return string
     */
    public function actionCancelMaintainAssign(){
        $model = new ShopScreenAdvertMaintain();
        return $model->getCancelMaintainAssign(Yii::$app->request->get());
    }



    /**
     * Finds the ShopScreenAdvertMaintain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopScreenAdvertMaintain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopScreenAdvertMaintain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
