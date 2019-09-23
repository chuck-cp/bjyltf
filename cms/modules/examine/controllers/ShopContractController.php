<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\models\LogAccount;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use Yii;
use cms\modules\examine\models\ShopContract;
use cms\modules\examine\models\search\ShopContractSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopContractController implements the CRUD actions for ShopContract model.
 */
class ShopContractController extends CmsController
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
     * Lists all ShopContract models.
     * @return mixed
     * 店铺合同
     */
    public function actionIndex()
    {
        $searchModel = new ShopContractSearch();
        $searchModel->shop_type = 1;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $shopComObj = $searchModel->search($arr,1)->asArray()->all();
            if(empty($shopComObj)){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['合同编号','乙方','业务员','店铺编号','店铺名称','统一社会信用代码','法人代表','身份证号码','通讯地址','店铺联系人','签订时间','接收人','柜号','店铺状态','合同状态','备注'];
            foreach($shopComObj as $k=>$v){
                $csv[$k]['contract_number']=$v['contract_number']."\t";//合同编号
                $csv[$k]['company_name']=$v['shopApply']['company_name']."\t";//乙方
                $csv[$k]['member_name']=$v['shop']['member_name']."\t";//业务员
                $csv[$k]['shop_id']=$v['shop']['id']."\t";//店铺名称
                $csv[$k]['name']=$v['shop']['name']."\t";//店铺名称
                $csv[$k]['registration_mark']=$v['shopApply']['registration_mark']."\t";//统一社会信用代码
                $csv[$k]['apply_name']=$v['shopApply']['apply_name']."\t";//法人代表
                $csv[$k]['identity_card_num']=$v['shopApply']['identity_card_num']."\t";//身份证号码
                $csv[$k]['address']=$v['shop']['address']."\t";//通讯地址
                $csv[$k]['contacts_name']=$v['shopApply']['contacts_name']."\t";//店铺联系人
                $csv[$k]['create_at']=$v['create_at']."\t";//签订时间
                $csv[$k]['receiver_name']=$v['receiver_name']."\t";//存档人
                $csv[$k]['cabinet_number']=$v['cabinet_number']."\t";//柜号
                $csv[$k]['shop_status']=Shop::getStatusByNum($v['shop']['status'])."\t";//店铺状态
                $csv[$k]['examine_status']=ShopContract::getContractStatus($v['examine_status'])."\t";//合同状态
                $csv[$k]['description']=$v['description']."\t";//备注
            }
            $file_name="Shop_Contract_".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //总部合同
    public function actionHeadContract()
    {
        $searchModel = new ShopContractSearch();
        $searchModel->shop_type = 2;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->headsearch($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $headComObj = $searchModel->headsearch($arr,1)->asArray()->all();
            if(empty($headComObj)){
                return $this->render('head-contract', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=['合同编号','乙方','业务员','统一社会信用代码','法人代表','身份证号码','通讯地址','签订时间','接收人','柜号','合同状态','备注'];
            foreach($headComObj as $k=>$v){
                $csv[$k]['contract_number']=$v['contract_number']."\t";//合同编号
                $csv[$k]['company_name']=$v['headquarters']['company_name']."\t";//乙方
                $csv[$k]['member_name']=$v['headquarters']['member_name']."\t";//业务员
                $csv[$k]['registration_mark']=$v['headquarters']['registration_mark']."\t";//统一社会信用代码
                $csv[$k]['name']=$v['headquarters']['name']."\t";//法人代表
                $csv[$k]['identity_card_num']=$v['headquarters']['identity_card_num']."\t";//身份证号码
                $csv[$k]['company_address']=$v['headquarters']['company_address']."\t";//通讯地址
                $csv[$k]['create_at']=$v['create_at']."\t";//签订时间
                $csv[$k]['receiver_name']=$v['receiver_name']."\t";//接收人
                $csv[$k]['cabinet_number']=$v['cabinet_number']."\t";//柜号
                $csv[$k]['examine_status']=ShopContract::getContractStatus($v['examine_status'])."\t";//合同状态
                $csv[$k]['description']=$v['description']."\t";//备注
            }
            $file_name="Head_Contract_".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('head-contract', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopContract model.
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
     * Creates a new ShopContract model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopContract();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShopContract model.
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
     * Deletes an existing ShopContract model.
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
     * Finds the ShopContract model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopContract the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopContract::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //添加合同信息
    public function actionAddContractId($id){
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax){
            $data=Yii::$app->request->post();
            $model->contract_number = $data['contract_number'];
            $model->cabinet_number = $data['cabinet_number'];
            $model->description = $data['description'];
            if($model->save(false)!==false){
                return json_encode(['code'=>1,'msg'=>'提交成功']);
            }else{
                return json_encode(['code'=>2,'msg'=>'提交失败']);
            }
        }
        return $this->renderPartial('contract', [
            'model' => $model,
        ]);
    }

    //合同审核
    public function actionConExamine(){
        $datas = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model =  ShopContract::findOne(['id'=>$datas['id']]);
            if($model){
                if($model->examine_status == 1){
                    return json_encode(['code'=>3,'msg'=>'已审核，请勿重复审核']);
                }else{
                    $model->examine_status = $datas['status'];
                    $model->receiver_name =Yii::$app->user->identity->username;
                    $model->examine_at =date('Y-m-d H:i:s',time());
                    $res = $model->save();
                    if($datas['status'] == 2){//驳回时，改状态不计算提成
                        $transaction->commit();
                        if($res){
                            return json_encode(['code'=>1,'msg'=>'提交成功']);
                        }else{
                            return json_encode(['code'=>2,'msg'=>'提交失败']);
                        }
                    }elseif($datas['status'] == 1){//通过时计算提成
                        //判断相关店铺是否已安装完成，是否给业务员提成
                        if($model->shop_type == 1){
                            //店铺
                            $shopObj = Shop::findOne(['id'=>$model->shop_id,'headquarters_id'=>0]);
                            //联系人
                            if(!empty($shopObj->introducer_member_mobile)){
                                $price_name='店铺签约奖励金';
                            }else{
                                $price_name='安装联系费';
                            }
                            if($shopObj->status == 5){
                                if(!LogAccount::writeLog(2,$shopObj->member_price,1,$price_name,$shopObj->member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                                    throw new Exception("[error]创建".$price_name."收入日志失败");
                                }
                                //联系人上级
                                /*if(!LogAccount::writeLog(4,$shopObj->parent_member_price,1,'邀请人联系奖励金',$shopObj->parent_member_id,$shopObj->screen_number,$shopObj->area,$shopObj->name)){
                                    throw new Exception("[error]创建邀请人奖励金收入日志失败");
                                }*/
                            }
                        }elseif($model->shop_type == 2){
                            //总部
                            $shopObj = Shop::findAll(['headquarters_id'=>$model->shop_id]);
                            foreach ($shopObj as $ks=>$vs){
                                //联系人
                                if(!empty($vs->introducer_member_mobile)){
                                    $price_name='店铺签约奖励金';
                                }else{
                                    $price_name='安装联系费';
                                }
                                if($vs->status == 5){
                                    if(!LogAccount::writeLog(2,$vs->member_price,1,$price_name,$vs->member_id,$vs->screen_number,$vs->area,$vs->name)){
                                        throw new Exception("[error]创建".$price_name."收入日志失败");
                                    }
                                    //联系人上级
                                    /*if(!LogAccount::writeLog(4,$vs->parent_member_price,1,'邀请人联系奖励金',$vs->parent_member_id,$vs->screen_number,$vs->area,$vs->name)){
                                        throw new Exception("[error]创建邀请人奖励金收入日志失败");
                                    }*/
                                }
                            }
                        }
                        $transaction->commit();
                        if($res){
                            return json_encode(['code'=>1,'msg'=>'提交成功']);
                        }else{
                            return json_encode(['code'=>2,'msg'=>'提交失败']);
                        }
                    }
                }
            }
        }catch (Exception $e) {
            Yii::error($e->getMessage(), 'error');
            $transaction->rollBack();
            return false;
        }
    }
    //合同解除
    public function actionConRelieve()
    {
        $datas = Yii::$app->request->post();
        $res = ShopContract::updateAll(['status'=>$datas['status']],['id'=>$datas['id']]);
        if($res){
            return json_encode(['code'=>1,'msg'=>'提交成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'提交失败']);
        }
    }
}
