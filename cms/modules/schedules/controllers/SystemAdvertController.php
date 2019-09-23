<?php

namespace cms\modules\schedules\controllers;

use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\schedules\models\SystemAdvertArea;
use cms\modules\schedules\models\SystemAdvertExamine;
use cms\modules\schedules\models\SystemTestShop;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use cms\modules\schedules\models\SystemAdvert;
use cms\modules\schedules\models\search\SystemAdvertSearch;
use yii\web\NotFoundHttpException;
use cms\models\AdvertPosition;
use cms\models\AdvertPrice;
use cms\models\SystemAddress;
use cms\modules\schedules\models\search\SystemAdvertExamineSearch;

/**
 * SystemAdvertController implements the CRUD actions for SystemAdvert model.
 */
class SystemAdvertController extends CmsController
{

    /**
     * Lists all SystemAdvert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemAdvertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $date = date('Y-m-d',strtotime("+1 day"));
        $sysdateModel = SystemAdvertExamine::find()->where(['date'=>$date])->orderBy('id desc')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sysdateModel' => $sysdateModel ? $sysdateModel->examine_status:1,
        ]);
    }

    public function actionPushCheck()
    {
        $date = date('Y-m-d',strtotime("+1 day"));
        $sysdateModel = SystemAdvertExamine::findOne(['date'=>$date]);
        if(empty($sysdateModel) || $sysdateModel->examine_status!=0) {
            $sysdate = new SystemAdvertExamine();
            $sysdate->date = $date;
            $sysdate->examine_status = 0;
            $sysdate->examine_number = 0;
            $sysdate->examine_user_id = 0;
            if($sysdate->save()){
                //添加日志
                $log_examine = new LogExamine();
                $log_examine->examine_key=9;
                $log_examine->foreign_id=$sysdate->id;
                $log_examine->examine_result=0;//申请
                $log_examine->create_user_id = Yii::$app->user->identity->getId();
                $log_examine->create_user_name = Yii::$app->user->identity->username;
                $log_examine->save();
                return json_encode(['code'=>1,'msg'=>'推送审核成功']);
            }else{
                var_dump($sysdate->getErrors());
                return json_encode(['code'=>2,'msg'=>'推送审核失败']);
            }
        }
    }
    /**
     * Displays a single SystemAdvert model.
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
     * Creates a new SystemAdvert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemAdvert();
        if(Yii::$app->request->isAjax){
             return SystemAdvert::Add(Yii::$app->request->post(),$model);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreate_c()
    {
        $model = new SystemAdvert();
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            //ToolsClass::p($data);die;
            return SystemAdvert::Add_c(Yii::$app->request->post(),$model);
        }
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }

        $dataone = AdvertPosition::find()->select('id,rate,key,type,time,spec')->asArray()->one();
        $time = explode(',',$dataone['time']);
        $dataone['time'] = AdvertPrice::stringasarray($time);
        $rate = explode(',',$dataone['rate']);
        foreach ($rate as $keyra=>$valuera){
            $dataone['rates'][$keyra+1] = $valuera/$rate[0];
        }
        $dataone['rate'] = AdvertPrice::stringasarray($dataone['rates']);
        return $this->render('create_c', [
            'province'=>$last,
            'model' => $model,
            'dataone' => $dataone,
        ]);
    }

    /**
     * Updates an existing SystemAdvert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax){
            return SystemAdvert::Edit(Yii::$app->request->post(),$model);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdate_c($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax){
            return SystemAdvert::Edit_c(Yii::$app->request->post(),$model);
        }

        $model->advert_position_id = AdvertPosition::findOne(['key'=>$model->advert_position_key])->id;
        //获取所有的省
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }

        //获取已投放的市区
        $SystemAddressmodel = new SystemAddress();
        foreach(SystemAdvertArea::find()->where(['advert_id'=>$id])->asArray()->all() as $k=>$v){
            $AraeAll[]=$SystemAddressmodel ->getAdvertById($v['area_id']);
        };

        $dataone = AdvertPosition::find()->select('id,rate,key,type,time,spec')->asArray()->one();
        $time = explode(',',$dataone['time']);
        $dataone['time'] = AdvertPrice::stringasarray($time);
        $rate = explode(',',$dataone['rate']);
        foreach ($rate as $keyra=>$valuera){
            $dataone['rates'][$keyra+1] = $valuera/$rate[0];
        }

        return $this->render('update_c', [
            'AraeAll'=>$AraeAll,
            'province'=>$last,
            'model' => $model,
            'dataone' => $dataone,
        ]);
    }

    /**
     * Deletes an existing SystemAdvert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->deleteAdvert()) {
            return json_encode(['code'=>1,'msg'=>'删除成功']);
        }
        return json_encode(['code'=>2,'msg'=>'删除失败']);
    }

    /**
     * @param $id
     * @return string
     * 推送
     */
    public function actionPush($id)
    {
        $model=$this->findModel($id);
        $model->throw_status=1;
        if($model->save())
            return json_encode(['code'=>1,'msg'=>'推送成功']);
        return json_encode(['code'=>2,'msg'=>'推送失败']);
    }

    public function actionAddresscity(){
        $parent_id = Yii::$app->request->get('parent_id');
        $advert_id = Yii::$app->request->get('advert_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        $arr2=$adrsModel::getAreasByPid($parent_id);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= SystemAdvertArea::AreaIdArr($k,$advert_id);
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        return json_encode($last,true);
    }

    /**
     * 查看已投放地区
     */
    public function actionPutinArea(){
        foreach(SystemAdvertArea::find()->asArray()->all() as $k=>$v){
            $AraeAll[]=$v['area_id'];
        };
        $Areas = empty($AraeAll)?[]:SystemAdvert::PutinArea(array_unique($AraeAll));
        return $this->renderPartial('putin-area', [
            'Areas' => $Areas,
        ]);
    }

    /**
     * Finds the SystemAdvert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemAdvert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemAdvert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //测试推送广告
    public function actionPropel(){
        $resone = 0;
        $resalls = 0;
        $shopAd = SystemTestShop::find()->orderBy('description desc')->asArray()->all();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            if ($arr['advice'] == 1) {
                if($arr['shop_id']) {
                    $shopAdOne = SystemTestShop::findOne(['shop_id' => $arr['shop_id']]);
                    $push_shop_list['head_id'] = 0;
                    $push_shop_list['shop_id'] = $arr['shop_id'] != '' ? $arr['shop_id'] : 0;
                    $push_shop_list['area_id'] = $shopAdOne->area_id;
                    $push_shop_list['type'] = 'test';
//                  json_encode($push_shop_list) = {"head_id":0,"shop_id":"10","area_id":"101440304004","type":"test"}
                    $resone = RedisClass::rpush("push_shop_list", json_encode($push_shop_list), 5);
                }
            } elseif ($arr['advice'] == 2) {
                foreach ($shopAd as $ka => $va) {
                    $push_shop_list['head_id'] = 0;
                    $push_shop_list['shop_id'] = $va['shop_id'] != '' ? $va['shop_id'] : 0;
                    $push_shop_list['area_id'] = $va['area_id'];
                    $push_shop_list['type'] = 'test';
                    $resalls = RedisClass::rpush("push_shop_list", json_encode($push_shop_list), 5);
                }
            }
        }
        return $this->render('propel',[
            'resone' => $resone,
            'resalls' => $resalls,
            'shopAd' => $shopAd,

        ]);
    }

    /**
     * @return string
     * 等待日广告审核列表
     */
    public function actionAdvertExamineList(){
        $searchModel = new SystemAdvertExamineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('advert-examine', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdvertExamine($id){
        $data  = Yii::$app->request->post();
        $Model = new SystemAdvertExamine();
        return $Model->getAdvertExamine($id,$data);
    }
}