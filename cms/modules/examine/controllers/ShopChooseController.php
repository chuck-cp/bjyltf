<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\modules\guest\models\Member;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\ShopApply;
use Yii;
use cms\modules\shop\models\ShopUpdateRecord;
use cms\modules\shop\models\search\ShopUpdateRecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\LogExamine;

/**
 * ShopChooseController implements the CRUD actions for ShopUpdateRecord model.
 */
class ShopChooseController extends CmsController
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
     * Lists all ShopUpdateRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopUpdateRecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopUpdateRecord model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $shopModel = new Shop();
        $smodel = $shopModel->findOne(['id'=>$model->shop_id]);
        $applyModel = new ShopApply();
        $amodel = $applyModel->findOne(['id'=>$model->shop_id]);
        if(empty($smodel) || empty($amodel)){
            return $this->error('无相关店铺',['shop-choose/index']);
        }
        return $this->render('view', [
            'model' => $model,
            'smodel' => $smodel,
            'amodel' => $amodel,
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>8])->orderBy('create_at desc')->asArray()->all(),
        ]);
    }

    /**
     * Creates a new ShopUpdateRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopUpdateRecord();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopUpdateRecord model.
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
     * Deletes an existing ShopUpdateRecord model.
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
     * Finds the ShopUpdateRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopUpdateRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopUpdateRecord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *商家审核
     */
    public function actionChooseExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['id']){
            return false;
        }
        $Model = ShopUpdateRecord::findOne(['id'=>$arr['id']]);
        if($Model){
            //是否已经审核过
            if($Model->examine_status > 0){
                return 5;
            }
            $status = $arr['type'] == 'pass' ? 1 : 2;
            if($status == 1){
                $member = Member::findOne(['mobile'=>$Model->update_apply_mobile]);
                if(empty($member)){
                    return 4;
                }
            }else{
                $member='';
            }
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = ShopUpdateRecord::examinerecord($Model,$status,$desc,$member);
            if($res == 1){
                return $res;
            }else{
                return $res =0;
            }
        }else{
            return false;
        }

    }
}
