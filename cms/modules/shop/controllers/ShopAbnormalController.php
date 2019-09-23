<?php

namespace cms\modules\shop\controllers;

use Yii;
use cms\modules\shop\models\ShopAbnormal;
use cms\modules\shop\models\search\ShopAbnormalSearch;
use cms\modules\shop\models\search\ScreenRunTimeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopAbnormalController implements the CRUD actions for ShopAbnormal model.
 */
class ShopAbnormalController extends Controller
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
     * Lists all ShopAbnormal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopAbnormalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopAbnormal model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($shop_id,$shop_name)
    {

        $searchModel = new ScreenRunTimeSearch();
        $searchModel->shop_id=$shop_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'shop_name' => $shop_name,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 查看开机时长详情
     */
    public function actionViewList($software_number,$shop_name)
    {
        $searchModel = new ScreenRunTimeSearch();
        $searchModel->software_number=$software_number;
        $dataProvider = $searchModel->viewSearch(Yii::$app->request->queryParams);

        return $this->renderPartial('view-list', [
            'shop_name'=>$shop_name,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }





    /**
     * Deletes an existing ShopAbnormal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionStatus()
    {
        $id=Yii::$app->request->post('id');

        $model=$this->findModel($id);
        $model->status=1;
        if($model->save())
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        return json_encode(['code'=>2,'msg'=>'操作失败']);
    }

    /**
     * Finds the ShopAbnormal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopAbnormal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopAbnormal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
