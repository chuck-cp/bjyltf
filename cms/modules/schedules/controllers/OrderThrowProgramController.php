<?php

namespace cms\modules\schedules\controllers;

use cms\core\CmsController;
use Yii;
use cms\modules\schedules\models\OrderThrowProgram;
use cms\modules\schedules\models\OrderThrowProgramList;
use cms\modules\schedules\models\search\OrderThrowProgramSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\SystemAddress;
use cms\modules\member\models\search\OrderSearch;
use cms\modules\schedules\models\OrderThrowProgramSpace;
use cms\modules\schedules\models\OrderThrowProgramSpaceList;
use cms\models\AdvertPosition;
/**
 * OrderThrowProgramController implements the CRUD actions for OrderThrowProgram model.
 */
class OrderThrowProgramController extends CmsController
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
     * A屏区域排期
     * Lists all OrderThrowProgram models.
     * @return mixed
     */
    public function actionAscreen()
    {

        $searchModel = new OrderThrowProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= OrderThrowProgram::getCountProgram($k,'a');
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        unset($AreaAll);unset($arr2);
        if(Yii::$app->request->isAjax){
            $area_id=Yii::$app->request->get('area_id');
            $data=Yii::$app->request->get();
            if(isset($area_id)){
                /*$date[]=date('Y-m-d',strtotime('+1 day'));
                $date[]=date('Y-m-d',strtotime('+2 day'));
                $date[]=date('Y-m-d',strtotime('+3 day'));
                $date[]=date('Y-m-d',strtotime('+4 day'));
                $date[]=date('Y-m-d',strtotime('+5 day'));
                $date[]=date('Y-m-d',strtotime('+6 day'));
                $date[]=date('Y-m-d',strtotime('+7 day'));*/
                //非正常逻辑，测试使用
                $date=OrderThrowProgram::prDates($data['startat'],$data['endat']);
                for($i=0;$i<=count($date)-1;$i++){
                    $ProgramAll[$date[$i]]['id']=OrderThrowProgram::getProgramAll($area_id,$date[$i],'a');
                }
                foreach($ProgramAll as $k2=>$v2){
                    for($i=1 ;$i<=20;$i++){
                        $ProgramListRst[$k2][$i]['total_time_sum']=OrderThrowProgramList::getTotalTimeSum($v2['id'],$i);
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramList::getOrderId($v2['id'],$i,'a');
                    }
                }
                return json_encode(['code'=>1,'ProgramListRst'=>$ProgramListRst]);
            }
        }else{
            return $this->render('ascreen', [
                'province'=>$last,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * 订单
     */
    public function actionOrder($order_id){
        $searchModel = new OrderSearch();
        $OrderIdAll=explode(',',$order_id);
        $dataProvider = $searchModel->ordersearch($OrderIdAll);
        return $this->renderPartial('aorder', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * B屏区域排期
     * Lists all OrderThrowProgram models.
     * @return mixed
     */
    public function actionBscreen()
    {
        $searchModel = new OrderThrowProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= OrderThrowProgram::getCountProgram($k,'b');
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        if(Yii::$app->request->isAjax){
            $area_id=Yii::$app->request->get('area_id');
            $data=Yii::$app->request->get();
            if(isset($area_id)){
                /*$date[]=date('Y-m-d',strtotime('+1 day'));
                $date[]=date('Y-m-d',strtotime('+2 day'));
                $date[]=date('Y-m-d',strtotime('+3 day'));
                $date[]=date('Y-m-d',strtotime('+4 day'));
                $date[]=date('Y-m-d',strtotime('+5 day'));
                $date[]=date('Y-m-d',strtotime('+6 day'));
                $date[]=date('Y-m-d',strtotime('+7 day'));*/
                //非正常逻辑，测试使用
                $date=OrderThrowProgram::prDates($data['startat'],$data['endat']);
                for($i=0;$i<=count($date)-1;$i++){
                    $ProgramAll[$date[$i]]['id']=OrderThrowProgram::getProgramAll($area_id,$date[$i],'b');
                }
                foreach($ProgramAll as $k2=>$v2){
                    for($i=1 ;$i<=1;$i++){
                        $ProgramListRst[$k2][$i]['count']=OrderThrowProgramList::getCount($v2['id']);
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramList::getOrderId($v2['id'],$i,'b');
                    }
                }
                return json_encode(['code'=>1,'ProgramListRst'=>$ProgramListRst]);
            }
        }else{
            return $this->render('bscreen', [
                'province'=>$last,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * C屏区域排期
     * Lists all OrderThrowProgram models.
     * @return mixed
     */
    public function actionCscreen()
    {
        $searchModel = new OrderThrowProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= OrderThrowProgram::getCountProgram($k,'b');
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        if(Yii::$app->request->isAjax){
            $area_id=Yii::$app->request->get('area_id');
            $data=Yii::$app->request->get();
            if(isset($area_id)){
                /*$date[]=date('Y-m-d',strtotime('+1 day'));
                $date[]=date('Y-m-d',strtotime('+2 day'));
                $date[]=date('Y-m-d',strtotime('+3 day'));
                $date[]=date('Y-m-d',strtotime('+4 day'));
                $date[]=date('Y-m-d',strtotime('+5 day'));
                $date[]=date('Y-m-d',strtotime('+6 day'));
                $date[]=date('Y-m-d',strtotime('+7 day'));*/
                //非正常逻辑，测试使用
                $date=OrderThrowProgram::prDates($data['startat'],$data['endat']);
                for($i=0;$i<=count($date)-1;$i++){
                    $ProgramAll[$date[$i]]['id']=OrderThrowProgram::getProgramAll($area_id,$date[$i],'c');
                }
                //ToolsClass::p($ProgramAll);die;
                foreach($ProgramAll as $k2=>$v2){
                    for($i=1 ;$i<=1;$i++){
                        $ProgramListRst[$k2][$i]['count']=OrderThrowProgramList::getCount($v2['id']);
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramList::getOrderId($v2['id'],$i,'c');
                    }
                }
                return json_encode(['code'=>1,'ProgramListRst'=>$ProgramListRst]);
            }
        }else{
            return $this->render('cscreen', [
                'province'=>$last,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }


    /**
     * D屏区域排期
     * Lists all OrderThrowProgram models.
     * @return mixed
     */
    public function actionDscreen()
    {
        $searchModel = new OrderThrowProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $arr2 = SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= OrderThrowProgram::getCountProgram($k,'d');
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        if(Yii::$app->request->isAjax){
            $area_id=Yii::$app->request->get('area_id');
            $data=Yii::$app->request->get();
            if(isset($area_id)){
                /*$date[]=date('Y-m-d',strtotime('+1 day'));
                $date[]=date('Y-m-d',strtotime('+2 day'));
                $date[]=date('Y-m-d',strtotime('+3 day'));
                $date[]=date('Y-m-d',strtotime('+4 day'));
                $date[]=date('Y-m-d',strtotime('+5 day'));
                $date[]=date('Y-m-d',strtotime('+6 day'));
                $date[]=date('Y-m-d',strtotime('+7 day'));*/
                //非正常逻辑，测试使用
                $date=OrderThrowProgram::prDates($data['startat'],$data['endat']);
                for($i=0;$i<=count($date)-1;$i++){
                    $ProgramAll[$date[$i]]['id']=OrderThrowProgram::getProgramAll($area_id,$date[$i],'d');
                }
                foreach($ProgramAll as $k2=>$v2){
                    for($i=1 ;$i<=1;$i++){
                        $ProgramListRst[$k2][$i]['count']=OrderThrowProgramList::getCount($v2['id']);
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramList::getOrderId($v2['id'],$i,'d');
                    }
                }
                return json_encode(['code'=>1,'ProgramListRst'=>$ProgramListRst]);
            }
        }else{
            return $this->render('dscreen', [
                'province'=>$last,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * 历史排期
     */
    public function actionHistory(){
        /*$aa='0,0,20,40,60,60,60,60,60,60';
        $bb=explode(',',$aa);
        $topicid = ' ';
        foreach ($bb as $val)
        {
            $topicid.=$val==60?'0'.',':'1'.',';
        }
        $strend=trim($topicid,',');//去除最后个逗号
        echo $strend;*/
        $arr2= SystemAddress::getAreasByPid(101);
        foreach($arr2 as $k=>$v){
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        if(Yii::$app->request->isAjax){
            $date7=date('Y-m-d',strtotime('+7 day'));
            $data=Yii::$app->request->get();
            $date=OrderThrowProgram::prDates($data['startat'],$data['endat']);
            if($data['advert_key']=='A'){
                for($i=0;$i<=count($date)-1;$i++){
                    $SpaceTime['a1']=OrderThrowProgramSpace::getProgramSpaceTime($data['area_id'],$date[$i],'a1');
                    $SpaceTime['a2']=OrderThrowProgramSpace::getProgramSpaceTime($data['area_id'],$date[$i],'a2');
                    $ProgramAll[$date[$i]]['data']=$SpaceTime;
                }
                foreach($ProgramAll as $k2=>$v2){
                    $j=0;
                    $o=0;
                    if($v2['data']['a1'] || $v2['data']['a2']){
                        if(!empty($v2['data']['a1'])){
                            $jdata=explode(',',$v2['data']['a1'][1]);
                        }else{
                            $jdata='';
                        }
                        if(!empty($v2['data']['a2'])){
                            $odata=explode(',',$v2['data']['a2'][1]);
                        }else{
                            $odata='';
                        }
                        for($i=1 ;$i<=20;$i++){
                            if($i%2==0){
                                if(!empty($v2['data']['a2'])){
                                    $ProgramListRst[$k2][$i]['total_time_sum']=60-$odata[$j];
                                    $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramSpaceList::getProgramSpaceListIdali($i/2-1,$v2['data']['a2'][0]);
                                    $j++;
                                }else{
                                    $ProgramListRst[$k2][$i]['total_time_sum']='';
                                    $ProgramListRst[$k2][$i]['order_id']='';
                                    $j++;
                                }
                            }else{
                                if(!empty($v2['data']['a1'])){
                                    $ProgramListRst[$k2][$i]['total_time_sum']=300-$jdata[$o];
                                    $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramSpaceList::getProgramSpaceListIdali(($i-1)/2,$v2['data']['a1'][0]);
                                    $o++;
                                }else{
                                    $ProgramListRst[$k2][$i]['total_time_sum']='';
                                    $ProgramListRst[$k2][$i]['order_id']='';
                                    $o++;
                                }
                            }
                        }
                    }else{
                        for($i=1 ;$i<=20;$i++){
                            $ProgramListRst[$k2][$i]['total_time_sum']='';
                            $ProgramListRst[$k2][$i]['order_id']='';
                        }
                    }
                   // ToolsClass::p($ProgramListRst);die;
                }
                return json_encode(['code'=>1,'advert_key'=>'a','ProgramListRst'=>$ProgramListRst]);
            }else{
                for($i=0;$i<=count($date)-1;$i++){
                    if($data['advert_key']=='C'){
                        $SpaceTime['c']=OrderThrowProgramSpace::getProgramSpaceTime($data['area_id'],$date[$i],'c');
                        $ProgramAll[$date[$i]]['data']=$SpaceTime;
                    }else if($data['advert_key']=='D'){
                        $SpaceTime['d']=OrderThrowProgramSpace::getProgramSpaceTime($data['area_id'],$date[$i],'d');
                        $ProgramAll[$date[$i]]['data']=$SpaceTime;
                    }else{
                        $SpaceTime['b']=OrderThrowProgramSpace::getProgramSpaceTime($data['area_id'],$date[$i],strtolower($data['advert_key']));
                        $ProgramAll[$date[$i]]['data']=$SpaceTime;
                    }
                }
                foreach($ProgramAll as $k2=>$v2){
                    if($data['advert_key']=='B'){
                        if(!empty($v2['data']['b'])){
                            $ProgramListRst[$k2][$i]['count']=(2700-array_sum(explode(',',$v2['data']['b'][1])))/30;
                            $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramSpaceList::getProgramSpaceListId($v2['data']['b'][0]);
                        }else{
                            for($i=1 ;$i<=1;$i++){
                                $ProgramListRst[$k2][$i]['count']='';
                                $ProgramListRst[$k2][$i]['order_id']='';
                            }
                        }
                    }else if($data['advert_key']=='C'){
                        $ProgramListRst[$k2][$i]['count']=$v2['data']['c']?(3600-array_sum(explode(',',$v2['data']['c'][1])))/60:0;
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramSpaceList::getProgramSpaceListIdIn([$v2['data']['c']?$v2['data']['c'][0]:'']);
                    }else if($data['advert_key']=='D'){
                        $ProgramListRst[$k2][$i]['count']=$v2['data']['d']?(3600-array_sum(explode(',',$v2['data']['d'][1])))/60:0;
                        $ProgramListRst[$k2][$i]['order_id']=OrderThrowProgramSpaceList::getProgramSpaceListIdIn([$v2['data']['d']?$v2['data']['d'][0]:'']);
                    }
                }
                return json_encode(['code'=>1,'advert_key'=>'b','ProgramListRst'=>$ProgramListRst]);
            }
        }
        $KeyName=AdvertPosition::getAdventKeyName();
        return $this->render('history', [
            'province'=> $last,
            'KeyName'=>$KeyName
        ]);
    }

    /**
     * Displays a single OrderThrowProgram model.
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
     * Creates a new OrderThrowProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderThrowProgram();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrderThrowProgram model.
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
     * Deletes an existing OrderThrowProgram model.
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
     * A屏 B屏地区切换
     */
    public function actionAddress($advert_key){
        $parent_id = Yii::$app->request->get('parent_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        $arr2=$adrsModel::getAreasByPid($parent_id);
        foreach($arr2 as $k=>$v){
            $last[$k]['status']= OrderThrowProgram::getCountProgram($k,$advert_key);
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        return json_encode($last,true);
    }

    /**
     * 历史屏排期地区切换
     */
    public function actionAddressls(){
        $parent_id = Yii::$app->request->get('parent_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        $arr2=$adrsModel::getAreasByPid($parent_id);
        foreach($arr2 as $k=>$v){
            $last[$k]['name'] = $v;
            $last[$k]['id'] = $k;
        }
        return json_encode($last,true);
    }
    /**
     * Finds the OrderThrowProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return OrderThrowProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderThrowProgram::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
