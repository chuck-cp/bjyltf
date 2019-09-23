<?php

namespace cms\modules\report\controllers;

use cms\models\OrderPlayPresentation;
use cms\models\OrderPlayPresentationList;
use cms\modules\member\models\OrderArea;
use cms\modules\member\models\OrderDate;
use cms\core\CmsController;
use cms\models\OrderThrowProgramCount;
use cms\models\SystemAddress;
use cms\modules\shop\models\Shop;
use common\libs\ToolsClass;
use cms\models\OrderThrowOrderDate;
use Yii;
use cms\modules\member\models\Order;
use cms\modules\member\models\search\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ActiveDataProvider;
use cms\models\OrderPlayView;
use yii\mongodb;
use cms\modules\report\models\MongoModels;
/**
 * ReportController implements the CRUD actions for Order model.
 */
class ReportController extends CmsController
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
        $searchModel->examine_status = 5;
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($arr,0, [4, 5], 'report');
        if(isset($arr['search']) && $arr['search'] ==1){
            $dataArr = $searchModel->search($arr,1, [4, 5], 'report')->asArray()->all();
            if (empty($dataArr)){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $title = ['编号','订单号','广告购买人','投放地区','广告位','广告时长','频次','购买天数','已播天数','投放日期','完成日期','屏幕数量','投放状态'];
            foreach ($dataArr as $k=>$v){
                $csv[$k]['id'] = $v['id'];
                $csv[$k]['order_code'] = $v['order_code'];
                $csv[$k]['member_name'] = $v['member_name'];
                $csv[$k]['area_name'] = $v['area_name'];
                $csv[$k]['advert_name'] = $v['advert_name'];
                $csv[$k]['advert_time'] = $v['advert_time'];
                $csv[$k]['rate'] = $v['rate'];
                $csv[$k]['total_day'] = $v['total_day'];
                if($v['examine_status']==4){
                    $csv[$k]['yb_day'] = ToolsClass::timediff(strtotime($v['orderDate']['start_at']), time(), 'day');
                }else{
                    $csv[$k]['yb_day'] = $v['total_day'];
                }
                $csv[$k]['start_at'] = $v['orderDate']['start_at'];
                $csv[$k]['end_at'] = $v['orderDate']['end_at'];
                $csv[$k]['screen_number'] = $v['screen_number'];
                $csv[$k]['examine_status'] = $v['examine_status']==4 ? '播放中' : '已完成';
            }
            ToolsClass::Getcsv($csv,$title,"Report".date("mdHis",time()).".csv");die;

            /*$exporter = new CsvGrid([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $dataObj,
                    'pagination' => [
                        'pageSize' => 100, // export batch size
                    ],
                ]),
            ]);
            if($exporter->dataProvider->getCount() == 0){
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
            $exporter->export()->send(chr(0xEF).chr(0xBB).chr(0xBF).'reports_'.date('Y-m-d').'_'.time().'.csv');*/
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /*
     * 点击查看详情
     */
    public function actionDetail($id){
        $model = $this->findModel($id);
        return $this->renderPartial('detail', [
            'model' => $model,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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



    public function actionSchedule($id){
//        $dates = OrderDate::find()->where(['order_id'=>$id])->select('order_id,start_at,end_at')->asArray()->one();//获取起始时间
        $datelist = OrderDate::getOrderDateSeries($id);//获取连续的时间
        //街道分页
        $street = OrderArea::getStreetsByOrderId($id);//获取街道
        if(!empty($street)){
            $streetArr = explode(',', $street['street_area']);
            $pageSize =20;
            $pages = new Pagination(['totalCount' => count($streetArr),'pageSize' => $pageSize]);
            $srr = array_slice($streetArr,$pages->offset,$pages->limit);
        }else{
            return $this->error('暂时无法获取地址详情，请刷新！',['index']);
        }
        if(Yii::$app->request->post()){
            $newdate = OrderThrowOrderDate::findtrue($id,$streetArr,$datelist);
            $title = ['投放地区/排期'];
            $title = array_merge($title,$datelist);
            foreach ($newdate as $k=>$v){
                $csv[$k]['area_id']=SystemAddress::getAreaNameById($k);
                foreach($datelist as $kd=>$vd){
                    /*if(isset($v[$vd]) == 1){
                        $csv[$k]['playnum'.$kd]='播放';

                    }else{
                        $csv[$k]['playnum'.$kd]='无排期';
                    }*/
                    $csv[$k]['playnum'.$kd] = empty($v[$vd])?'无排期':'播放';
                }
            }
            $file_name="投放地区/排期".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }else{
            $newdate = OrderThrowOrderDate::findtrue($id,$srr,$datelist);
        }
        return $this->render('schedule', [
            'pages' => $pages,
            'srr' => $srr,
            'datelist' => $datelist,
            'newdate' => $newdate,
        ]);
    }

    //已播放完成报告
    public function actionReportlist($id){
        $datelist = OrderDate::getOrderDateSeries($id);//获取连续的时间
        //街道分页
        $newdate = OrderPlayPresentationList::find()->where(['order_id'=>$id])->asArray()->all();
        if(!empty($newdate)){
            $pageSize =3;
            $pages = new Pagination(['totalCount' => count($newdate),'pageSize' => $pageSize]);
            $srr = array_slice($newdate,$pages->offset,$pages->limit);
            $lastpage = (int)ceil(count($newdate)/$pageSize);
        }else{
            return $this->error('暂时无法获取报告！',['index']);
        }
        $newtotal = OrderPlayPresentation::find()->where(['order_id'=>$id])->asArray()->one();
        if(Yii::$app->request->post()){
            $title = ['投放地区/播放率'];
            $title = array_merge($title,$datelist);
            $title = array_merge($title,['播放总量','应播总量','播放率%']);
            foreach ($newdate as $k=>$v){
                $csv[$k]['area_id']=SystemAddress::getAreaNameById($v['area_id']);
                foreach($datelist as $kd=>$kv){
                    $csv[$k]['playnum'.$kd]=explode(',',$v['data_list'])[$kd];
                }
                $csv[$k]['play_total']=$v['play_total'];
                $csv[$k]['should_total']=$v['should_total'];
                $csv[$k]['percentage']=$v['percentage'];
            }
            $total[0]['area_id'] = '合计 '.count($csv).' 街道';
            foreach(explode(',',$newtotal['data_list']) as $kt=>$vt){
                $total[0]['playnum'.$kt]=$vt;
            }
            $total[0]['play_total'] = $newtotal['play_total'];
            $total[0]['should_total'] = $newtotal['should_total'];
            $total[0]['percentage'] = $newtotal['percentage'];
            $csv = array_merge($csv,$total);
            $file_name="投放地区/播放率统计".date("mdHis",time()).".csv";
            ToolsClass::Getcsv($csv,$title,$file_name);
        }
        return $this->renderPartial('reportlist', [
            'pages' => $pages,
            'srr' => $srr,
            'lastpage' => $lastpage,
            'datelist' => $datelist,
            'newtotal' => $newtotal,
        ]);

    }
    /*
     * 前台播放报告
     */
    public function actionFrontReport(){
        $id = Yii::$app->request->get('id');
        //播放总量和店铺总量
        $playTotal = OrderPlayPresentation::getFieldValue($id, 'play_total');
        $shopTotal = OrderPlayView::getFieldValue($id, 'throw_shop_number');
        return $this->render('front-report',[
            'id' => $id,
            'playTotal' => $playTotal,
            'shopTotal' => $shopTotal,
        ]);
    }
    /*
     * 播放详情
     */
    public function actionReportDetail(){
        $params = Yii::$app->request->get();
        $id = $params['id'];
        if(isset($params['shopNum']) && isset($params['playNum'])){
            echo 1111*$params['shopNum'].'----'.$params['playNum']*1111;
        }
        echo 1111;
        return $this->renderPartial('report-detail');
    }
    /*
     * 改变修改系数，修改后的数据文件上传腾讯云
     */
    public function actionModifySave(){
        $params = Yii::$app->request->post();
        //var_dump(Yii::$app->request->getUrl());die;
        $params['id'] = 593;
        //1.修改系数
        $obj = OrderPlayView::find()->where(['order_id'=>$params['id']])->one();
        //print_r($obj);die;
        //if(!$obj) {return false;}
        $obj->play_number_multiple = $params['playNum'];
        $obj->shop_number_multiple = $params['shopNum'];
        $result = $obj->save();
        //2.文件上传腾讯云
        //var_dump(file_exists('./template/tamplate.php'));die;
        $obj = fopen('./template/tamplate.php','r');
        $str=fread($obj,filesize("./template/tamplate.php"));
        $str=str_replace("{play}",88888,$str);
        $str=str_replace("{shop}",999999,$str);
        fclose($obj);
        //新建空白文件，将$str写入
        $handle=fopen('222'.$params['id'].'.html',"w");
        fwrite($handle,$str);
        fclose($handle);
        //$res = \Yii::$app->cos->upload('222'.$params['id'].'.html','/system/order_throw_html/222'.$params['id'].'.html');
        //unlink('222'.$params['id'].'.html');
    }

    public function actionTamplate(){
        return $this->renderPartial('tamplate');
    }


    public function actionOrderView($id){
        $model = $this->findModel($id);
        return $this->render('order-view', [
            'model' => $model,
        ]);
    }

    /**
     * 到达率报告
     */
    public function actionArrivalratereport($id){
        $searchModel = new MongoModels();
        $arr = Yii::$app->request->queryParams;
        $table = 'report';
        $asArr = $searchModel->search($arr,$table,$id);
        return $this->render('arrivalratereport', [
            'id'=>$id,
            'asArr' => $asArr,
           // 'dataProvider' => $dataProvider,
        ]);
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
}
