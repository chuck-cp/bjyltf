<?php

namespace cms\modules\examine\controllers;

use cms\modules\examine\models\ShopLogistics;
use cms\models\LogExamine;
use cms\modules\ledmanage\models\SystemDevice;
use cms\modules\screen\models\Screen;
use cms\modules\screen\models\search\ScreenSearch;
use Yii;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\search\ShopSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
/**
 * InstallController implements the CRUD actions for Shop model.
 */
class InstallController extends CmsController
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
     * Lists all shop models.
     * @return mixed
     * 安装待确认
     */
    public function actionIndex(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 1;
        $searchModel->install_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Date: 2018-07-31
     * 安装反馈审核内部
     * wpw
     */
    public function actionOfflineAn(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 1;
        $searchModel->install_status = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('offline-an', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all shop models.
     * 配发货
     */
    public function actionAllocate(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 3;
        $searchModel->install_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('allocate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single shop model.
     */
    public function actionView($id)
    {
        $desc = LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>4])->orderBy('create_at desc')->asArray()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'wlmodel' => new ShopLogistics(),
//            'azmodel' => new ShopApply(),
            'scmodel' => new Screen(),
            'desc'=>$desc
        ]);
    }

    /**
     * Updates an existing shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
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
     * Deletes an existing shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the shop model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //配货记录(取消配货)
    /*public function actionStockRemoval(){
        $array = YII::$app->request->post();
        $upafter = ShopLogistics::screenRemove($array);
        if($upafter){
            return $this->success('添加成功',['install/view','id'=>$array['shopid']]);
        }else{
            return $this->error('添加失败');
        }
    }*/

    //添加发货信息
    public function actionAddscreen()
    {
        $array = YII::$app->request->post();
        $upafter = ShopLogistics::addLogistics($array);
        if($upafter){
            return $this->success('添加成功',['install/view','id'=>$array['shopid']]);
        }else{
            return $this->error('添加失败');
        }
}

    //验证设备编号是否重复发货/
    public function actionCheckScreenid()
    {
        $arr = Yii::$app->request->post();
        foreach($arr['number'] as $key=>$value){
            $screen = Screen::findOne(array('number'=>$value));
            if($screen){
                return json_encode(['number'=>$value,'errorid'=>2]);//存在重复的设备编码
            }
            $devicenum = SystemDevice::find()->where(['device_number'=>$value])->asArray()->one();
            if(empty($devicenum)){
                return json_encode(['number'=>$value,'errorid'=>3]);//不在设备库内
            }elseif($devicenum['is_output']!=1){
                return json_encode(['number'=>$value,'errorid'=>4]);//设备未出库
            }
        }
        return 1;//不存在重复的设备编码
    }

    //验证发货的屏幕是否正确
    public function actionCheckRightId(){
        $arr = Yii::$app->request->post();
        $screen = Screen::findAll(array('shop_id'=>$arr['shopid']));
        $newarray = array_column($screen,'number');
        if(count($arr['number'])==count($newarray)){
            foreach($arr['number'] as $key =>$value){
                if(!in_array($value,$newarray)){
                    return json_encode(['number'=>$value,'errorid'=>3]);//发货设备号与配货设备号不一致
                }
            }
            return 1;
        }else{
            return 2;//发货数量与配货数量不等
        }
    }

    //确认安装，加钱
    public function actionAnzhuang()
    {
        $arr = Yii::$app->request->post();
        if(Shop::checkInstall($arr)){
            if($arr['line'] ==1){
                return $this->success('安装成功',['install/index']);
            }else{
                return $this->success('安装成功',['install/offline-an']);
            }
        }else{
            if($arr['line'] ==1){
                return $this->error('安装失败',['install/index']);
            }else{
                return $this->error('安装失败',['install/offline-an']);
            }
        }
    }

    //修改配货shebei
    public function actionShebei($shop_id){
        $screennum = Screen::getScreenInfo($shop_id);
        return $this->renderPartial('shebei', [
            'shop_id' => $shop_id,
            'screennum' => $screennum,
        ]);
    }

    //添加物流信息
    public function actionWuliu($shop_id){
        return $this->renderPartial('wuliu', [
            'shop_id' => $shop_id,
            'wlmodel' => new ShopLogistics(),
        ]);
    }

    /**
     * 安装反馈驳回
     * wpw
     * 2018-08-01
     */
    public function actionReject(){
        $DataArr = Yii::$app->request->post();
        if(!$DataArr['id']){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $LogExamine=new LogExamine();
        $LogExamine->examine_key=4;
        $LogExamine->foreign_id=$DataArr['id'];
        $LogExamine->examine_result=2;
        $LogExamine->examine_desc=$DataArr['data'];
        $LogExamine->create_user_id=Yii::$app->user->identity->getId();
        $LogExamine->create_user_name=Yii::$app->user->identity->username;
        $LogExamine->create_at=date('Y-m-d H:i:s');
        if($LogExamine->save() && Shop::updateAll(['status'=>4,'examine_number'=>0,'last_examine_user_id'=>0],['id'=>$DataArr['id']])){
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    /**
     * 获取店铺屏幕状态
     */
    public function actionShopScreen($shop_id){
        $screenModel = new ScreenSearch();
        $screenModel->shop_id = $shop_id;
        $dataProvider = $screenModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('shop-screen', [
            'screenModel' => $screenModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 修改镜面数量
     */
    public function actionModifyScreen(){
        $arr = Yii::$app->request->post();
        if(!$arr['shop_id'] || !is_int(intval($arr['num']))){
            return 0;
        }
        $model = Shop::findOne(['id'=>$arr['shop_id']]);
        $model->screen_number = intval($arr['num']);
        $re = $model->save(false);
        return $re;
    }

}
