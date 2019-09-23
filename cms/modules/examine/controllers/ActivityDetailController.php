<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\modules\examine\models\Activity;
use common\libs\ToolsClass;
use Yii;
use cms\modules\examine\models\ActivityDetail;
use cms\modules\examine\models\search\ActivityDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\member\models\search\MemberSearch;
/**
 * ActivityDetailController implements the CRUD actions for ActivityDetail model.
 */
class ActivityDetailController extends CmsController
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
     * Lists all ActivityDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActivityDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityDetail model.
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
     * Creates a new ActivityDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActivityDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActivityDetail model.
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
     * Deletes an existing ActivityDetail model.
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
     * 指派对接人页面
     */
    public function actionAssignButt($id){
        $searchModel = new MemberSearch();
       // $searchModel->acivity_status=1;
        $dataProvider = $searchModel->activitysearch(Yii::$app->request->queryParams);
        return $this->renderPartial('assign-butt', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }

    //指派对接人
    public function actionActivityAssign(){
        $data=Yii::$app->request->get();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $model=$this->findModel($data['id']);
        $model->custom_member_id=$data['member_id'];
        $model->custom_member_name=$data['name'];
        $model->order_source=2;
        if($model->save(false))
            return json_encode(['code'=>1,'msg'=>'指派成功']);
        return json_encode(['code'=>2,'msg'=>'指派失败']);
    }

    /**
     * 取消对接人
     */
    public function actionCancel(){
        $id=Yii::$app->request->post('id');
        $model=$this->findModel($id);
        $model->custom_member_id=0;
        $model->custom_member_name='';
        $model->order_source=0;
        if($model->save(false))
            return json_encode(['code'=>1,'msg'=>'取消成功']);
        return json_encode(['code'=>2,'msg'=>'取消失败']);

    }



    /**
     * Finds the ActivityDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ActivityDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
