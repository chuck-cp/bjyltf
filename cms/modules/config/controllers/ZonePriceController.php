<?php

namespace cms\modules\config\controllers;

use cms\models\SystemAddress;
use cms\modules\config\models\search\SystemZoneListSearch;
use cms\modules\config\models\search\SystemZonePriceSearch;
use cms\modules\config\models\SystemConfig;
use common\libs\ToolsClass;
use Yii;
use cms\modules\config\models\SystemZonePrice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\config\models\SystemZoneList;
use cms\core\CmsController;
use cms\modules\config\models\SystemAddressLevel;
/**
 * ZonePriceController implements the CRUD actions for SystemZonePrice model.
 */
class ZonePriceController extends CmsController
{
    //public $enableCsrfValidation = false;
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
     * Lists all SystemZonePrice models.
     * @return mixed
     */
    public function actionZone()
    {
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            SystemConfig::updateAll(['content'=>$arr['subsidy_date']],['id'=>'subsidy_date']);
        }
        $subdate = $configModel->getAllConfig('subsidy_date');
        return $this->render('zone', [
            'RegionalpriceAll'=>$configModel->Regionalprice(),
            'subdate' =>$subdate,
        ]);
    }

    /**
     * Lists all SystemZonePrice models.
     * @return Subsidy
     * 补助设置
     */
//    public function actionSubsidy()
//    {
//        $searchModel = new SystemZoneListSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $configModel = new SystemConfig();
//        if(Yii::$app->request->isPost){
//            $arr = Yii::$app->request->post();
//            $configModel->load($arr);
//            $configModel->saveConfig();
//        }else{
//            $subsidy_date = $configModel->loadConfigData('subsidy_date');
//        }
//        return $this->render('subsidy', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//            'subsidy_date' =>$subsidy_date,
//        ]);
//    }
    //查看详情
    public function actionView($id)
    {
      //  $arr = SystemZonePrice::getAreaPrice($id);
        $arr=SystemAddressLevel::getAreaPrice($id);
        $data = Yii::$app->request->get();
        return $this->render('view', [
            'arr' => $arr,
            'data'=> $data
        ]);

    }
//    public function actionSubview($id,$type)
//    {
//        $arr = SystemZonePrice::getAreaPrice($id,$type);
//        return $this->render('subview', [
//            'arr' => $arr,
//            'price' => $id,
//        ]);
//    }
    /**
     *选择地区页面
     */
    public function actionChoose($price_id){
        //查找省级单位
        $province = SystemAddress::getAreasByPid(101);
        return $this->renderPartial('choose', [
            'province' => $province,
            'price_id' => $price_id,
        ]);
    }
    /**
     * 地区切换
     */
    public function actionAddress(){
        $parent_id = Yii::$app->request->get('parent_id');
        $price_id = Yii::$app->request->get('price_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        if(strlen($parent_id) == 7){
            $priceList = SystemZoneList::findOne(['id'=>$price_id]);
            //查找当前价格下已选中的区域
            if($price_id){
//                $priceList = SystemZoneList::findOne($price_id);
//                if($priceList->price_type==1){
                    $alreadyArea = SystemZonePrice::find()->where(['price_id'=>$price_id])->select('area_id')->asArray()->all();
//                }else{
//                    $alreadyArea = SystemZonePrice::find()->where(['subsidy_id'=>$price_id])->select('area_id')->asArray()->all();
//                }
                foreach ($alreadyArea as $k => $v){
                    if(substr($v['area_id'],0,7) != $parent_id){
                        unset($alreadyArea[$k]);
                    }
                }
            }
            //$arr1 = $adrsModel::getAreasByPid($parent_id);
            $arr2 = $adrsModel::getAreasByPid($parent_id);
            $last = [];
            foreach ($arr2 as $k => $v){
                $middle = SystemZonePrice::getPriceById($k);
                $last[$k]['name'] = $v;
                $last[$k]['price'] = $middle;
                if($middle > 0){
                    $last[$k]['check'] = 1;
                    if($priceList->price !== $middle){
                        $last[$k]['disable'] = 1;
                    }else{
                        $last[$k]['disable'] = 0;
                    }
                }else{
                    $last[$k]['check'] = 0;
                    $last[$k]['disable'] = 0;
                }
            }
            return json_encode($last,true);
        }
        return json_encode($adrsModel::getAreasByPid($parent_id),true);
    }

    /**
     * 区县级选中后提交保存
     */
    public function actionRemark(){
        $arr = Yii::$app->request->get();
        if(!$arr['area_id']){
            return false;
        }

        $priceList = SystemZoneList::findOne(['id'=>$arr['price_id']]);
        $model = SystemZonePrice::findOne(['area_id'=>$arr['area_id']]);
        if(empty($model)){
            $model = new SystemZonePriceSearch();
            $model->load($arr);
        }
        if($priceList){
            $model->area_id = $arr['area_id'];
//            if($priceList->price_type == 1){
                $model->price_id = $arr['isck'] == true ? $arr['price_id'] : 0;
//                $model->price = $arr['isck'] == true ? $priceList->price : 0;
//            }else{
//                $model->subsidy_id = $arr['isck'] == true ? $arr['price_id'] : 0;
//                $model->subsidy_price = $arr['isck'] == true ? $priceList->price : 0;
//            }
            $re = $model->save();
            if($re){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 批量删除地区
     */
    public function actionBatchDelete(){
        $drr = Yii::$app->request->post();
        if(!empty($drr)){
            return SystemZonePrice::modifyAreaPrice($drr);
        }
        return false;
    }
    /**
     * 确定修改价格
     */
    public function actionModifyPrice(){
        $arr = Yii::$app->request->get();
      //  ToolsClass::p($arr);die;
        /*if(!$arr['price'] || !$arr['price_id'] || !$arr['month_price'] ){
            return false;
        }*/

        //修改店铺价格
        if(SystemConfig::updateAll(['content'=>$arr['price']*100],['id'=>'system_price_first_install_'.$arr['id']])!==false && SystemConfig::updateAll(['content'=>$arr['month_price']*100],['id'=>'system_price_subsidy_'.$arr['id']])!==false){
            return true;
        }else{
            return false;
        }
        /*$model = SystemZoneList::findOne(['id'=>$arr['price_id']]);
        if($model){
            if(SystemZoneList::modfiyPrice($model,$arr['price'],$arr['month_price'])){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }*/
    }
    /**
     *新增区域价格
     */
    public function actionCreate(){
        $model = new SystemZoneList();
        if(Yii::$app->request->isPost){
            $arr = Yii::$app->request->post();
            $arr['SystemZoneList']['price'] = $arr['SystemZoneList']['price'] * 100;
            $arr['SystemZoneList']['month_price'] = $arr['SystemZoneList']['month_price'] * 100;;
            $arr['SystemZoneList']['create_user_id'] = Yii::$app->user->identity->getId();
            if ($model->load($arr) && $model->save()) {
               return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     *新增补助价格
     */
//    public function actionSubcreate(){
//        $model = new SystemZoneList();
//        if(Yii::$app->request->isPost){
//            $arr = Yii::$app->request->post();
//            $arr['SystemZoneList']['price'] = $arr['SystemZoneList']['price'] * 100;
////            $arr['SystemZoneList']['price_type'] = 2;
//            $arr['SystemZoneList']['create_user_id'] = Yii::$app->user->identity->getId();
//            if ($model->load($arr) && $model->save()) {
//                return $this->redirect(['subview', 'id' => $model->id]);
//            }
//        }
//        return $this->render('subcreate', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Updates an existing SystemZonePrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->area_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SystemZonePrice model.
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
     * Finds the SystemZonePrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemZonePrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemZonePrice::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //删除价格
    public function actionDelprice($priceid){
        $model = SystemZoneList::findOne(['id'=>$priceid]);
        if($model!==null){
//            $type = $model->price_type;
            $res = $model->delete();
            if($res){
//                if($type == 1){
                    SystemZonePrice::updateAll(['price_id'=>0],['price_id'=>$priceid]);
//                }else{
//                    SystemZonePrice::updateAll(['subsidy_id'=>0,'subsidy_price'=>0],['subsidy_id'=>$priceid]);
//                }
                return 1;
            }else{
                return 2;
            }
        }else{
            return 3;
        }
    }

    //更新地址
    public function actionUpadd(){
        $model = new SystemZonePrice();
        $addarray = SystemAddress::find()->where(['level'=>5])->select('id')->asArray()->all();
        $padd = SystemZonePrice::find()->asArray()->all();
        $padds = array_column($padd,'area_id');
        foreach($addarray as $key=>$value){
            if(in_array($value['id'],$padds)){
                echo '已存在</br>';
            }else{
                $model->area_id = $value['id'];
                $model->price_id = 0;
                $res = $model->save();
                echo $res.'</br>';
            }
        }
        echo "执行完成！！";
    }
}
