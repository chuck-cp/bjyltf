<?php

namespace cms\modules\screen\controllers;

use cms\modules\member\models\MemberShopCount;
use cms\modules\member\models\search\MemberSearch;
use cms\modules\shop\models\Shop;
use Yii;
use cms\modules\screen\models\Screen;
use cms\modules\screen\models\search\ScreenSearch;
use cms\modules\shop\models\search\ShopSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;

/**
 * ScreenrController implements the CRUD actions for Screen model.
 */
class ScreenController extends CmsController
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
     * Lists all Screen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopSearch();
        $searchModel->status = [5];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 获取店铺屏幕状态
     */
    public function actionScreen($shop_id){
        $screenModel = new ScreenSearch();
        $screenModel->shop_id = $shop_id;
        $dataProvider = $screenModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('screen', [
            'screenModel' => $screenModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Screen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Screen model.
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
     * Finds the Screen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Screen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Screen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 获取店铺管理人员详情
     */
    public function actionDesignate($shop_id){
        $membermodel = new MemberSearch();
        $searchInfo = Yii::$app->request->queryParams;
        $shop_ids = explode(',',$shop_id);
        if(count($shop_ids)<=1){
            if (empty($searchInfo['MemberSearch']['name']) && empty($searchInfo['MemberSearch']['mobile']) && empty($searchInfo['MemberSearch']['province'])) {
                $shopinfo = Shop::findOne(['id' => $shop_id]);
                $area = substr($shopinfo->area, 0, 9);
                $membermodel->admin_area = $area;
            } else {
                if (!empty($searchInfo['area'])) {
                    $membermodel->admin_area = $searchInfo['area'];
                } elseif (!empty($searchInfo['city'])) {
                    $membermodel->admin_area = $searchInfo['city'];
                } elseif (!empty($searchInfo['province'])) {
                    $membermodel->admin_area = $searchInfo['province'];
                } else {
                    $membermodel->admin_area = '';
                }
            }
        }
        $membermodel->member_type = 2;
        $dataProvider = $membermodel->screenSearch($searchInfo);
        return $this->renderPartial('designate', [
            'model' => $membermodel,
            'dataProvider' => $dataProvider,
            'shopid'=>$shop_id,
            'searchInfo' => $searchInfo,
        ]);
    }

    //提交指派管理人员
    public function actionUpadminshop(){
        $allid = Yii::$app->request->get();
        var_dump($allid);die;
        //指派商家的管理者
        if(Screen::upAdminShop($allid)){
            return 1;
        }else{
            return 2;
        }

    }

}
