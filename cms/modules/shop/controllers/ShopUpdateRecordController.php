<?php

namespace cms\modules\shop\controllers;

use cms\core\CmsController;
use cms\modules\member\models\Member;
use Yii;
use cms\modules\shop\models\ShopUpdateRecord;
use cms\modules\shop\models\search\ShopUpdateRecordSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\shop\models\search\ShopSearch;
use cms\models\LogExamine;

/**
 * ShopUpdateRecordController implements the CRUD actions for ShopUpdateRecord model.
 */
class ShopUpdateRecordController extends CmsController
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
    
    //发起变更
    public function actionInitiateChange()
    {
        $id = Yii::$app->request->get('id');
        $desc = '';
        if($id){
            $model = ShopUpdateRecord::findOne(['id'=>$id]);
            $aimgs = explode(',',$model->update_authorize_image);
            for($i=0;$i<count($aimgs);$i++){
                if($i == 0){
                    $model->update_authorize_image = $aimgs[$i];
                }else{
                    $ks = 'update_authorize_image'.($i+1);
                    $model->$ks = $aimgs[$i];
                }
            }
            $oimgs = explode(',',$model->update_other_image);
            for($j=0;$j<count($oimgs);$j++){
                if($j == 0){
                    $model->update_other_image = $oimgs[$j];
                }else{
                    $ko = 'update_other_image'.($j+1);
                    $model->$ko = $oimgs[$j];
                }
            }
            $desc = LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>8])->orderBy('create_at desc')->asArray()->all();
            $model->province = substr($model->update_area_id,0,5);
            $model->city = substr($model->update_area_id,0,7);
            $model->area = substr($model->update_area_id,0,9);
            $model->town = $model->update_area_id;

        }else{
            $model = new ShopUpdateRecord();
        }
        return $this->render('initiate-change',[
            'model' => $model,
            'desc' => $desc,
            'id' => $id,
        ]);
    }
    //
    public function actionInitiateChangeView()
    {
        $id = Yii::$app->request->get('id');
        if($id){
            $model = ShopUpdateRecord::findOne(['id'=>$id]);
            $desc = LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>8])->orderBy('create_at desc')->asArray()->all();
        }else{
            $model = new ShopUpdateRecord();
            $desc = '';
        }

        return $this->render('initiate-change-view',[
            'model' => $model,
            'desc' => $desc,
            'id' => $id,
        ]);
    }

    //选择商家
    public function actionChooseShops()
    {
        $searchModel = new ShopSearch();
        $searchModel->headquarters_id = 0;
        $searchModel->status = 5;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        return $this->renderPartial('choose-shops',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //验证手机号是否注册
    public function actionMobileCheckRegister()
    {
        $mobile = Yii::$app->request->get('mobile');
        $member = Member::findOne(['mobile'=>$mobile]);
        if($member){
            return json_encode(['name'=>$member->name,'mobile'=>$member->mobile]);
        }else{
            return json_encode(['name'=>'未注册','mobile'=>'']);
        }
    }

    //修改法人内容提交
    public function actionAdminMember()
    {
        $datas = Yii::$app->request->post();
        $id = Yii::$app->request->get('id');
        if($id){
            $datas['id'] = $id;
        }
        $model = new ShopUpdateRecord();
        return $model->getAdminMember($datas);
    }
}