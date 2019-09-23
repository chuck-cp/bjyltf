<?php

namespace cms\modules\shop\controllers;

use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\examine\models\ShopHeadquartersList;
use cms\modules\shop\models\Shop;
use Yii;
use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\examine\models\search\ShopHeadSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopHeadquartersController implements the CRUD actions for ShopHeadquarters model.
 */
class ShopHeadquartersController extends CmsController
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
     * Lists all ShopHeadquarters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopHeadquarters model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);//总部
        $arraylist = ShopHeadquartersList::getShopByListId($id);
        $pageSize =10;
        $pages = new Pagination(['totalCount' => count($arraylist),'pageSize' => $pageSize]);
        $arraylist = array_slice($arraylist,$pages->offset,$pages->limit);
        $lastpage = (int)count($arraylist);
        return $this->render('view', [
            'model' => $model,
            'arraylist' => $arraylist,
            'lastpage' => $lastpage,
            'pages' => $pages,
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>7])->orderBy('create_at desc')->asArray()->all()
        ]);
    }

    //分店listview
    public function actionListview($id)
    {
        return $this->render('listview', [
            'model' => Shop::findOne(['id'=>$id]),
        ]);
    }
    /**
     * Creates a new ShopHeadquarters model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopHeadquarters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopHeadquarters model.
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
     * 开启关闭店铺广告
     */
    public function actionStoreAdverTotal($id){
        $model = $this->findModel($id);
        $model->agreed=Yii::$app->request->post('agreed')==0?1:0;
        if($model->save())
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        return json_encode(['code'=>2,'msg'=>'操作失败']);
    }
    /**
     * Deletes an existing ShopHeadquarters model.
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
     * Finds the ShopHeadquarters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopHeadquarters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopHeadquarters::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
