<?php

namespace cms\modules\shop\controllers;

use cms\core\MongoActiveRecord;
use cms\modules\authority\models\AuthAssignment;
use cms\modules\examine\models\ShopScreenReplace;
use cms\modules\screen\models\Screen;
use cms\modules\shop\models\ShopLable;
use cms\modules\shop\models\ShopUpdateRecord;
use common\libs\RedisClass;
use common\libs\ToolsClass;
use Yii;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\search\ShopSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\models\SystemAddress;
use common\libs\CsvClass;
/**
 * ShopController implements the CRUD actions for shop model.
 */
class ShopController extends CmsController
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
     */
    public function actionIndex(){
/**************************************************************************************/
        $searchModel = new ShopSearch();
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        $LableArr = ShopLable::find()->asArray()->all();
        if(isset($arr['search']) && $arr['search'] == 0){
            $userid = Yii::$app->user->identity->getId();
            $items = AuthAssignment::find()->where(['user_id'=>$userid])->select('item_name')->asArray()->all();
            $itemsarray = array_column($items,'item_name');
            if(in_array('超级管理员',$itemsarray)){

            }else {
                if (empty($arr['ShopSearch']['create_at_start']) && empty($arr['ShopSearch']['shop_examine_at_start']) && empty($arr['ShopSearch']['install_finish_at_start']) && empty($arr['ShopSearch']['contract_start'])) {
                    return "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('导出开始时间不能为空!')</script>";
                }
                if ((!empty($arr['ShopSearch']['create_at_start']) && !empty($arr['ShopSearch']['shop_examine_at_start'])) || (!empty($arr['ShopSearch']['create_at_start']) && !empty($arr['ShopSearch']['install_finish_at_start'])) || (!empty($arr['ShopSearch']['create_at_start']) && !empty($arr['ShopSearch']['contract_start'])) || (!empty($arr['ShopSearch']['shop_examine_at_start']) && !empty($arr['ShopSearch']['install_finish_at_start'])) || (!empty($arr['ShopSearch']['shop_examine_at_start']) && !empty($arr['ShopSearch']['contract_start'])) || (!empty($arr['ShopSearch']['install_finish_at_start']) && !empty($arr['ShopSearch']['contract_start']))) {
                    return "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('导出时间不能同时存在2个起始时间!')</script>";
                }
                //上个月第一天
                $firstday = date('Y-m-01', strtotime(date('Y', time()) . '-' . (date('m', time()) - 1) . '-01'));
                $create_at_seven = ToolsClass::timediffunit($arr['ShopSearch']['create_at_start'], $arr['ShopSearch']['create_at_end']);
                $create_at_firs = ToolsClass::timediffunit($arr['ShopSearch']['create_at_start'], $firstday);
                $shop_examine_seven = ToolsClass::timediffunit($arr['ShopSearch']['shop_examine_at_start'], $arr['ShopSearch']['shop_examine_at_end']);
                $shop_examine_firs = ToolsClass::timediffunit($arr['ShopSearch']['shop_examine_at_start'], $firstday);
                $install_finish_seven = ToolsClass::timediffunit($arr['ShopSearch']['install_finish_at_start'], $arr['ShopSearch']['install_finish_at_end']);
                $install_finish_firs = ToolsClass::timediffunit($arr['ShopSearch']['install_finish_at_start'], $firstday);
                $contract_seven = ToolsClass::timediffunit($arr['ShopSearch']['contract_start'], $arr['ShopSearch']['contract_end']);
                $contract_firs = ToolsClass::timediffunit($arr['ShopSearch']['contract_start'], $firstday);
                if ($create_at_seven > 7 || $create_at_firs > 0 || $shop_examine_seven > 7 || $shop_examine_firs > 0 || $install_finish_seven > 7 || $install_finish_firs > 0 || $contract_seven > 7 || $contract_firs > 0) {
                    return "<script src='/static/js/jquery/jquery-2.0.3.min.js'></script><script src='/static/layer/layer.js'></script><script>layer.alert('导出时间不能超过7天，或者起始时间不能超过上个月！')</script>";
                }
            }
            $file_name = "Shop".date("mdHis",time()).".csv";
            $DataCount = $searchModel->search($arr,1)->count();
            if($DataCount == 0){ 
                return $this->render('index', [
                    'LableArr'=>$LableArr,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['商家编号','用户ID','业务合作人','业务合作人手机号',  '管理人ID','店铺门脸','店铺名称','省','市','区','街道','店铺所在地区','详细地址','申请数量','实际屏幕数量','故障数量','申请状态','屏幕状态','店铺面积','申请客户端','镜面数量','入驻方式'/*,'申请编号','动态码'*/,'申请人姓名','申请人手机号','申请时间','店铺审核通过时间','安装人姓名','安装人电话','店铺安装完成时间','标签'];
            $count=ceil($DataCount/1000);
            $j=0;
            for($i=1;$i<=$count;$i++){
                $searchModel->offset=$j;
                $searchModel->limit=1000;
                $data=$searchModel->search($arr,2);
                $j=$i*1000;
                //处理csv要导出的数据
                $CsvData = CsvClass::ShopIndexData($data);
                if($i==1){
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name);
                }else{
                    CsvClass::CsvDataWriting($CsvData,$title,$file_name,false);
                }
                unset($CsvData);
            }
            CsvClass::CsvDownload($file_name);
        }
        return $this->render('index', [
            'LableArr'=>$LableArr,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);


    }
    /**
     * Displays a single shop model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionChooseView($id)
    {
        $modelc = ShopUpdateRecord::findOne(['id'=>$id]);
        return $this->render('choose-view', [
            'model' => $modelc,
        ]);
    }

    /**
     * Updates an existing shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    /**
     * Finds the shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //换屏
    /*public function actionRescreen($shop_id)
    {
        $modelarray = Screen::find()->where(['and',['shop_id'=>$shop_id],['<','status','3']])->asArray()->all();
        if(empty($modelarray)){
            echo '<h2 style="margin-top: 30%;text-align: center;letter-spacing: 14px;">暂无需要更换的屏幕</h2>';
            return false;
        }
        return $this->renderPartial('rescreen', [
            'modelarray' => $modelarray,
            'shop_id' => $shop_id,
        ]);
    }*/
    
    //维护屏幕
    public function actionUpscreen($shop_id,$type){
        return $this->renderPartial('upscreen',[
            'shop_id'=> $shop_id,
            'type'=> $type,
        ]);
    }

    //申请维护屏幕
    public function actionScreenReplace(){
        $bad = Yii::$app->request->post();
        $res = ShopScreenReplace::replaceScreen($bad);
        if($res){
            return $this->success('申请成功',['/shop/shop/view','id'=>$bad['shop_id']]);
        }else{
            return $this->error('申请失败',['/shop/shop/view','id'=>$bad['shop_id']]);
        }
    }

    //标签
    public function actionLables($shopid){
        $lables = ShopLable::find()->select('id,title,desc')->asArray()->all();
        $shopModel = Shop::findOne(['id'=>$shopid]);
        $labid = empty($shopModel->lable_id)?[]:explode(',',$shopModel->lable_id);
        return $this->renderPartial('lables',[
            'lables'=> $lables,
            'shopid'=> $shopid,
            'labid'=> $labid,
        ]);
    }
    //生成标签
    public function actionAddlable(){
        $datas = Yii::$app->request->get();
        $title = ToolsClass::trimall($datas['title']);
        $desc = ToolsClass::trimall($datas['desc']);
        if(empty($title) || empty($desc)){
            return json_encode(['code'=>2,'msg'=>'请填写名称和注释！']);
        }
        $lableModel = new ShopLable();
        $lableModel->title = $title;
        $lableModel->desc = $desc;
        $res = $lableModel->save();
        if($res){
            return json_encode(['code'=>1,'msg'=>'添加成功！']);
        }else{
            return json_encode(['code'=>3,'msg'=>'添加失败！']);
        }
    }

    /**
     * 保存标签
     */
    public function actionKeeplable($shopid){
        $Data = Yii::$app->request->post();
        $shopModel = Shop::findOne(['id'=>$shopid]);
        $label_id=implode(',',$Data['strAll']);
        $shopModel->lable_id=$label_id;
        if($shopModel->save() && ShopScreenReplace::updateAll(['lable_id'=>$label_id],['shop_id'=>$shopid])!==false)
            return json_encode(['code'=>1,'msg'=>'标签保存成功']);
        return json_encode(['code'=>2,'msg'=>'标签保存失败']);
    }

    /**
     * 删除标签
     */
    public function actionLabelDel(){
        $id= Yii::$app->request->post('id');
        if(ShopLable::deleteAll(['id'=>$id]))
            return json_encode(['code' => 1, 'msg' => '删除成功']);
        return json_encode(['code' => 2, 'msg' => '删除失败']);
    }

    /**
     * 开启关闭店铺广告
     */
    public function actionStoreAdver($id){
        $agreed=Yii::$app->request->post('agreed')==0?1:0;
        if(Shop::updateAll(['agreed'=>$agreed],['id'=>$id]))
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        return json_encode(['code'=>2,'msg'=>'操作失败']);
    }

    /**
     * 店铺数据统计
     */
    public function actionStatistics(){

        $searchModel = new ShopSearch();
        $map=Yii::$app->request->queryParams;
        $province=isset($map['ShopSearch']['province'])?SystemAddress::find()->where(['id'=>$map['ShopSearch']['province']])->asArray()->one()['name']:'';
        $dataProvider = $searchModel->StatisticsSearch($map);
        return $this->render('statistics',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider['data'],
            'stat' => $dataProvider['stat'],
            'province'=>$province
        ]);
    }


    //签约店铺
    public function actionSigningShop($create_at_start,$create_at_end,$areas){
        $searchModel = new ShopSearch();
        $searchModel->store_type=1;
        $searchModel->areas=$areas;
        $searchModel->create_at_start=$create_at_start;
        $searchModel->create_at_end=$create_at_end;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->StatisticsViewSearch($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $shopData = $searchModel->StatisticsViewSearch($arr,1)->asArray()->all();
            if(empty($shopData)){
                return $this->render('signing-shop', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['店铺ID','时间','店铺名称','店铺联系人','联系人电话','所属地区','店铺位置','经度','纬度','店铺类型','操作业务员'];
            $file_name="签约店铺详情".date("mdHis",time()).".csv";
            $csvDate=$this->shopData($shopData,1);
            ToolsClass::Getcsv($csvDate,$title,$file_name);
        }
        return $this->render('signing-shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //安装完成
    public function actionInstallShop($create_at_start,$create_at_end,$areas){
        $searchModel = new ShopSearch();
        $searchModel->store_type=2;
        $searchModel->areas=$areas;
        $searchModel->create_at_start=$create_at_start;
        $searchModel->create_at_end=$create_at_end;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->StatisticsViewSearch($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $shopData = $searchModel->StatisticsViewSearch($arr,1)->asArray()->all();
            if(empty($shopData)){
                return $this->render('install-shop', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['店铺ID','时间','店铺名称','店铺联系人','联系人电话','所属地区','店铺位置','经度','纬度','店铺类型','操作业务员'];
            $file_name="安装店铺详情".date("mdHis",time()).".csv";
            $csvDate=$this->shopData($shopData,2);
            ToolsClass::Getcsv($csvDate,$title,$file_name);
        }
        return $this->render('install-shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    //店铺导出数据的处理
    protected function shopData($data,$type){
        foreach($data as $k=>$v){
            $DataArr[$k]['id']=$v['id'];
            $DataArr[$k]['date']=$type==1?$v['shop_examine_at']:$v['install_finish_at'];
            $DataArr[$k]['name']=$v['name'];
            $DataArr[$k]['contacts_name']=$v['apply']['contacts_name'];
            $DataArr[$k]['contacts_mobile']=$v['apply']['contacts_mobile'];
            $DataArr[$k]['area_name']=$v['area_name'];
            $DataArr[$k]['address']=$v['address'];
            $DataArr[$k]['bd_longitude']=$v['bd_longitude'];
            $DataArr[$k]['bd_latitude']=$v['bd_latitude'];
            $DataArr[$k]['shop_operate_type']=Shop::getTypeByNum($v['shop_operate_type']);
            $DataArr[$k]['member_name']=$type==1?$v['member_name']:$v['install_member_name'];
        }
        return $DataArr;
    }

    //关闭店铺
    public function actionCloseShop()
    {
        $shopid = Yii::$app->request->post('id');
        $res = Shop::updateAll(['status'=>6],['id'=>$shopid]);
        if($res){
            $mongo = new MongoActiveRecord();
            $mongo->mongoDelete('shop',['id'=>$shopid]);
            return json_encode(['code'=>1,'msg'=>'关店成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'关店失败']);
        }
    }
    
    //更新设备redis坐标
    public function actionUpdateCoordinate()
    {
        $shopid = Yii::$app->request->post('shop_id');
        $rid = Yii::$app->request->post('rid');
        $newarray = ['shop_id'=>$shopid,'software_number'=>$rid];
        $res = RedisClass::rpush('list_json_get_coordinate_to_mongo',json_encode($newarray),1);
        if($res){
            return json_encode(['code'=>1,'msg'=>'更新成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'更新失败']);
        }
    }
}
