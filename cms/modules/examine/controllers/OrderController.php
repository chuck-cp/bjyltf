<?php

namespace cms\modules\examine\controllers;


use cms\modules\examine\models\ShopAdvertImage;
use cms\modules\examine\models\search\ShopAdvertImageSearch;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\Order;
use cms\modules\member\models\search\OrderSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\models\LogExamine;
use common\libs\Zip;
use cms\models\OrderMessage;
use common\libs\RedisClass;
use cms\modules\shop\models\Shop;
use cms\modules\examine\models\ShopHeadquarters;
/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends CmsController
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $examine_status=[];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0,$examine_status);
        //判断是否有审核状态的搜索条件
        $arr = Yii::$app->request->queryParams;
        $examine_status=['1','2','3'];
        if(!empty($arr['OrderSearch']['examine_status'])){
            $examine_status=$arr['OrderSearch']['examine_status'];
        }
        if(isset($arr['search']) && $arr['search']==0){
            $arr = Yii::$app->request->queryParams;
            $examine_status=['1','2','3'];
            if($arr['OrderSearch']['examine_status']) {
                $examine_status=$arr['OrderSearch']['examine_status'];
            }
            $array=$searchModel->search(Yii::$app->request->queryParams,1,$examine_status)->asArray()->all();
            if(empty($array)){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title=array('业务合作人','合作人电话','订单号','广告位','广告时长','日期','状态');
            foreach($array as $k=>$v){
                $csv[$k]['salesman_name']=$v['salesman_name'];
                $csv[$k]['salesman_mobile']=$v['salesman_mobile'];
                $csv[$k]['order_code']=$v['order_code'];
                $csv[$k]['advert_name']=$v['advert_name'];
                $csv[$k]['advert_time']=$v['advert_time'];
                $csv[$k]['tfrq_time']=$v['orderDate']['start_at'].'至'.$v['orderDate']['end_at'];
                if($v['examine_status']==1){
                    $csv[$k]['examine_status']="待审核";
                }else if($v['examine_status']==2){
                    $csv[$k]['examine_status']="被驳回";
                }else if($v['examine_status']==3){
                    $csv[$k]['examine_status']="已通过";
                }
            }
            $file_name="广告审核".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $data=$this->findModel($id);
        switch ($data['examine_status']){
           case 0;
                $examine_status='待提交素材';
                break;
           case 1;
                $examine_status='待审核';
                break;
           case 2;
                $examine_status='被驳回';
                break;
           case 3;
                $examine_status='已通过';
                break;
           case 4;
                $examine_status='已投放';
                break;
           case 5;
                $examine_status='投放完成';
                break;
        };
        return $this->render('view', [
            'model' => $this->findModel($id),
            'examine_status'=>$examine_status,
            'rejectAll'=>LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>5])->asArray()->all()
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 广告审核驳回
     */
     public function actionDismissaladd(){
         $arr=Yii::$app->request->get();
         if($arr['type']=='bohui'){
             $type=2;
         }else{
             $type=1;
         }
         if($arr['desc']==5){
             $examine_desc=$arr['descc'];
         }else{
             $examine_desc=Order::examinedesc()[$arr['desc']];
         }
         $Logmodel=new LogExamine();
         $Logmodel->examine_key=5;
         $Logmodel->foreign_id=$arr['foreign_id'];
         $Logmodel->examine_result=$type;
         $Logmodel->examine_desc=$examine_desc;
         //获取用户ID
         $Logmodel->create_user_id=Yii::$app->user->identity->getId();
         //获取用户姓名
         $Logmodel->create_user_name=Yii::$app->user->identity->username;
         if($Logmodel->save() && Order::updateAll(['examine_status'=>$type],['id'=>$arr['foreign_id']])){
             $orserMagmodel=new OrderMessage();
             $orserMagmodel->order_id=$arr['foreign_id'];
             $orserMagmodel->type=2;
             $orserMagmodel->desc='审核完成，广告资料进入被驳回状态';
             $orserMagmodel->reject_reason=$examine_desc;
             $orserMagmodel->save();
             return 1;
         }
         return 0;
     }

     /**
      * 广告通过
      */
     public function actionAdopt(){
         $transaction = Yii::$app->db->beginTransaction();
         try{
             $arr=Yii::$app->request->post();
             if($arr['type']=='pass'){
                $type=3;
             }
             Order::updateAll(['examine_status'=>$type],['id'=>$arr['order_id']]); //修改订单的审核状态
             //RedisClass::rpush("order_resource",$arr['order_id'],4);

             //添加审核日志
             $Logmodel=new LogExamine();
             $Logmodel->examine_key=5;
             $Logmodel->foreign_id=$arr['order_id'];
             $Logmodel->examine_result=1;
             $Logmodel->create_user_id=Yii::$app->user->identity->getId(); //获取用户ID
             $Logmodel->create_user_name=Yii::$app->user->identity->username;     //获取用户姓名
             $Logmodel->save();

             //添加order_message表
             $orserMagmodel=new OrderMessage();
             $orserMagmodel->order_id=$arr['order_id'];
             $orserMagmodel->type=2;
             $orserMagmodel->desc='审核通过，广告资料进入待投放状态';
             $orserMagmodel->save();

             //写redis
             $orderModel = Order::findOne(['id'=>$arr['order_id']]);
             RedisClass::rpush("order_resource_attribute_list",json_encode(['order_id'=>$arr['order_id'],'url'=>$orderModel->resource,'advert_key'=>$orderModel->advert_key]),1);
             $transaction->commit();
             return true;
         }catch (Exception $e){
             Yii::error($e->getMessage(),'error');
             $transaction->rollBack();
             return false;
         }
     }

    /**
     * 获取签名
     * @return string
     */
    public function actionQm() {
        $current = time();
        $expired = $current + 86400;
        $arg_list = [
            "secretId" => Yii::$app->cos_gg->secret_id,
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            "random" => rand()
        ];
        $orignal = http_build_query($arg_list);
        $tk['token'] = base64_encode(hash_hmac('SHA1', $orignal, Yii::$app->cos_gg->secret_key, true).$orignal);
        return json_encode($tk);
    }
    /**
     * 上传视频后更新腾讯云视频id
     */
    public function actionVideoid(){
        $arr=Yii::$app->request->get();
        if(Order::updateAll(['video_id'=>$arr['video_id'],'resource'=>$arr['resource']],['id'=>$arr['id']])){
            return json_encode(['code'=>1]);
        }else{
            return json_encode(['code'=>2]);
        }
    }

    
    //商家自定义广告
    public function actionShopOrder(){
        $searchModel = new ShopAdvertImageSearch();
        $map=Yii::$app->request->queryParams;
        $searchModel->shop_type = 1;
        $dataProvider = $searchModel->search($map,0);
        if(isset($map['search']) && $map['search'] == 0){
            $data = $searchModel->search($map,1)->asArray()->all();
            if(empty($data)){
                return $this->render('shop-order', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $csv=ShopAdvertImage::getCsv($data);
            $title=['商家ID','商家名称','所属地区','详细地址','屏幕数量','图片数量'];
            $file_name="商家广告".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('shop-order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //总部自定义广告
    public function actionHeadOrder(){
        $searchModel = new ShopAdvertImageSearch();
        $map=Yii::$app->request->queryParams;
        $searchModel->shop_type = 2;
        $dataProvider = $searchModel->headsearch($map,0);
        if(isset($map['search']) && $map['search'] == 0){
            $data = $searchModel->headsearch($map,1)->asArray()->all();
            if(empty($data)){
                return $this->render('head-order', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $csv=ShopAdvertImage::getCsv($data);
            $title=['商家ID','商家名称','所属地区','详细地址','屏幕数量','图片数量'];
            $file_name="商家广告".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->render('head-order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * 确认下载
     */
    public function actionConfirm(){
        $params = Yii::$app->request->get();
        $url = $params['url'];
        $filename = $params['filename'];
        //$resource = str_replace('i1.bjyltf.com','m0.bjyltf.com',$resource);
        //  $aid = $params['aid'];
        // Order::updateAllCounters(['download_number' => 1], ['order_code' => $order_id]);
        //流的方式发送给浏览器
        header("Content-Type: application/octet-stream");
        //按照字节的返回给浏览器
        header("Accept-Ranges: bytes");
        //告诉浏览器文件的大小
        header("Accept-Length: ".$this->get_fileSize($url));
        //以附件的形式发送给浏览器(也就是弹出，下载的对话框)
        header("Content-Disposition: attachment; filename="."$filename");
        //打开文件获取文件句柄
        $handle=fopen($url,"r");
        //将文件直接读取完
        //echo fread($handle,$this->get_fileSize($resource));
        //一部分一部分的读取
        while(!feof($handle)){
            $content=fread($handle,1024);
            echo $content;
        }
        fclose($handle);
    }
    private function get_fileSize($url){
        if(!isset($url)||trim($url)==''){
            return '';
        }
        ob_start();
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_NOBODY,1);
        $okay=curl_exec($ch);
        curl_close($ch);
        $head=ob_get_contents();
        ob_end_clean();
        $regex='/Content-Length:\s([0-9].+?)\s/';
        $count=preg_match($regex,$head,$matches);
        return isset($matches[1])&&is_numeric($matches[1]) ? $matches[1] : '';
    }

    public function actionShopAdvertImageView($shop_id,$shop_type){
        if($shop_type==1){
            $model=Shop::findOne(['id'=>$shop_id]);
        }else{
            $model=ShopHeadquarters::findOne(['id'=>$shop_id]);
        }
        $ImageArr=ShopAdvertImage::find()->where(['shop_id'=>$shop_id,'shop_type'=>$shop_type])->select('id,image_url,shop_type,shop_id,create_at,sort')->orderBy('sort desc')->asArray()->all();
        return $this->render('image-view', [
            'ImageArr' => $ImageArr,
            'ImageCount' => count($ImageArr),
            'type' => $shop_type,
            'model' => $model,
        ]);
    }

    public function actionImageDel(){
        $data=$arr=Yii::$app->request->post();
        if($data['shop_type']==1){
            $push_shop_list['shop_id']=$data['shop_id'];
            $push_shop_list['head_id']=0;
        }else{
            $push_shop_list['shop_id']=0;
            $push_shop_list['head_id']=$data['shop_id'];
        }
        if(ShopAdvertImage::deleteAll(['id'=>$data['id']])){
            RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);//下次更换
            return json_encode(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'删除失败']);
        }
    }

    //自定义广告手动推送
    public function actionPushProgram(){
        $shop_id = Yii::$app->request->post('shop_id');
        $shop_type = Yii::$app->request->post('shop_type');
        if($shop_type==1){
            $shopObj = Shop::findOne(['id'=>$shop_id]);
            $push_shop_list['shop_id']=$shopObj->id;
            $push_shop_list['head_id']=$shopObj->headquarters_id;
        }else{
            $push_shop_list['shop_id']=0;
            $push_shop_list['head_id']=$shop_id;
        }
        $res =  RedisClass::rpush("push_shop_custom_advert_list",json_encode($push_shop_list),5);
        if($res){
            return json_encode(['code'=>1,'msg'=>'推送成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'推送失败']);
        }
    }
}
