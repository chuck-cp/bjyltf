<?php

namespace cms\modules\sign\controllers;

use cms\core\CmsController;
use cms\modules\sign\models\search\SignBusinessCountSearch;
use cms\modules\sign\models\search\SignBusinessSearch;
use cms\modules\sign\models\search\SignMemberCountSearch;
use cms\modules\sign\models\search\SignTeamCountMemberDetailSearch;
use cms\modules\sign\models\search\SignTeamMemberSearch;
use cms\modules\sign\models\Sign;
use cms\modules\sign\models\SignBusiness;
use cms\modules\sign\models\SignMaintain;
use cms\modules\sign\models\SignMemberCount;
use cms\modules\sign\models\SignTeamCountShopDetail;
use common\libs\ToolsClass;
use console\models\SignBusinessCount;
use Yii;
use cms\modules\sign\models\SignTeam;
use cms\modules\sign\models\search\SignTeamSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\config\models\SystemConfig;
use cms\modules\sign\models\search\SignMaintainSearch;
use cms\modules\sign\models\search\SignTeamCountShopDetailSearch;
use cms\modules\sign\models\search\SignTeamBusinessCountSearch;
use cms\modules\sign\models\search\SignMaintainCountSearch;
use cms\modules\sign\models\search\SignTeamMaintainCountSearch;
use cms\modules\sign\models\search\SignSearch;
use cms\modules\sign\models\search\SignLogSearch;
use cms\modules\sign\models\SignImage;
/**
 * SignController implements the CRUD actions for SignTeam model.
 */
class SignController extends CmsController
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

    // 团队管理
    public function actionSignteam()
    {
        $searchModel = new SignTeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('signteam', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //签到设置
    public function actionSetting($team_id){
        $model = SignTeam::findOne(['id'=>$team_id]);
        if ($model->load(Yii::$app->request->post()) && $model->save()){
//            return $this->redirect(['signteam']);
            return $this->success('修改成功',['signteam']);
        }
        return $this->renderPartial('setting', [
            'model' => $model,
            'team_id' => $team_id,
        ]);
    }
    //团队详情
    public function actionTeams($team_id){
        $searchModel = new SignTeamMemberSearch();
        $searchModel->team_id = $team_id;
        $datas = Yii::$app->request->queryParams;
        $date['start'] = isset($datas['SignTeamMemberSearch']['create_at'])?$datas['SignTeamMemberSearch']['create_at']:date('Y-m-d');
        $date['end'] = isset($datas['SignTeamMemberSearch']['create_at_end'])?$datas['SignTeamMemberSearch']['create_at_end']:date('Y-m-d');
        $dataProvider = $searchModel->search($datas);
        return $this->render('teams', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'date' => $date,
        ]);
    }
    //个人签到日期选择
    public function actionMemberDate($team_id,$member_id,$startdate,$enddate){
        $date = SignMemberCount::find()->where(['and',['team_id'=>$team_id,'member_id'=>$member_id],['>=','create_at',$startdate],['<=','create_at',$enddate]])->asArray()->all();
//        $commandQuery = clone $date;
//        echo $commandQuery->createCommand()->getRawSql();die;
        $teamlist = SignTeam::findOne(['id'=>$team_id]);
        $pageSize =20;
        $pages = new Pagination(['totalCount' => count($date),'pageSize' => $pageSize]);
        $arraylist = array_slice($date,$pages->offset,$pages->limit);
        return $this->render('member-date', [
            'pages' => $pages,
            'arraylist' => $arraylist,
            'teamType' => $teamlist->team_type,
        ]);
    }

    //查看某个人某个日期签到数据--业务
    public function actionSignBusinessDate($team_id,$member_id,$date){
        $signlist = Sign::find()->joinWith('signBusiness')->where(['and',['team_id'=>$team_id],['team_type'=>1],['member_id'=>$member_id],['like','create_at',$date.'%', false]])->asArray()->all();
        return $this->render('sign-business-date', [
            'signlist' => $signlist,
            'team_id' => $team_id,
            'member_id' => $member_id,
        ]);
    }

    //查看某个人某个日期签到数据--维护
    public function actionSignMaintainDate($team_id,$member_id,$date){
        $signlist = Sign::find()->joinWith('signMaintain')->where(['and',['team_id'=>$team_id],['member_id'=>$member_id],['team_type'=>2],['like','create_at',$date.'%', false]])->asArray()->all();
        return $this->render('sign-maintain-date', [
            'signlist' => $signlist,
            'team_id' => $team_id,
            'member_id' => $member_id,
        ]);
    }

    //业务签到管理列表
    public function actionSignBusiness(){
        $searchModel = new SignSearch();
        if(Yii::$app->user->identity->sign_team != 0){
            $sign_team = explode(',',Yii::$app->user->identity->sign_team);
        }else{
            $sign_team = 0;
        }
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->BusinessSearch($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $DataArr = $searchModel->BusinessSearch($arr,1)->asArray()->all();
            if(empty($DataArr)){//如果导出数据为空直接加载页面
                return $this->render('sign-business', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'sign_team' => $sign_team,
                ]);
            }
            Sign::exportData($DataArr,1);
        }

        return $this->render('sign-business', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sign_team' => $sign_team,
        ]);
    }

    // 业务签到管理详情
    public function actionSignBusinessView($id){
        return $this->render('sign-business-view', [
            'model' => Sign::findOne(['id'=>$id])
        ]);
    }

    // 维护签到管理列表
    public function actionSignMaintain(){
        if(Yii::$app->user->identity->sign_team != 0){
            $sign_team = explode(',',Yii::$app->user->identity->sign_team);
        }else{
            $sign_team = 0;
        }
        $searchModel = new SignSearch();
        $arr = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->MaintainSearch($arr);
        if(isset($arr['search']) && $arr['search'] == 0){
            $DataArr = $searchModel->MaintainSearch($arr,1)->asArray()->all();
            if(empty($DataArr)){//如果导出数据为空直接加载页面
                return $this->render('sign-maintain', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'sign_team' => $sign_team,
                ]);
            }
            Sign::exportData($DataArr,2);
        }
        return $this->render('sign-maintain', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sign_team' => $sign_team,
        ]);
    }

    //维护签到管理详情
    public function actionSignMaintainView($id){
        return $this->render('sign-maintain-view', [
            'model' => Sign::findOne(['id'=>$id]),
        ]);
    }

    //地图
    public function actionMap($longitude,$latitude){
        return $this->renderPartial('map', [
            'longitude' => $longitude,
            'latitude' => $latitude
        ]);
    }

    //按时间业务签到统计
    public function actionBusinessTime(){
        $searchModel = new SignBusinessCountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('business-time', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider['data'],
            'stat' => $dataProvider['stat'],
        ]);
    }
    //按时间业务签到统计---签到详情
    public function actionBusinessTimeList($date){
        $searchModel = new SignSearch();
        $searchModel -> date = $date;
        $dataProvider = $searchModel->BusinessSearch(Yii::$app->request->queryParams);
        return $this->render('business-time-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //按时间业务签到统计---未签到成员详情
    public function actionBusinessTimeNosign(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=1;
        $dataProvider = $searchModel->search($search);
        return $this->render('business-time-nosign', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //按时间业务签到统计---超时签到详情

    public function actionBusinessTimeOvertime(){
        $date = Yii::$app->request->get();
        $searchModel = new SignSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $dataProvider = $searchModel->BusinessOvertimeSearch($search);
        return $this->render('business-time-overtime', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    //按时间业务签到统计---未达标成员详情
    public function actionBusinessTimeUnqualified(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=1;
        $dataProvider = $searchModel->unqualifiedsearch($search);
        return $this->render('business-time-unqualified', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    //按时间业务签到统计---早退成员详情
    public function actionBusinessTimeLeaveEarly(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=1;
        $dataProvider = $searchModel->leaveearlysearch($search);
        return $this->render('business-time-leave-early', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //按时间业务签到统计---重复签到详情
    public function actionBusinessTimeRepeatSign(){
        $date = Yii::$app->request->get();
        $ShopDetailModel = new SignTeamCountShopDetailSearch();
        $searchModel = new SignSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel->create_at = $ShopDetailModel -> create_at = $date['date'];//统计日期
            $searchModel->create_at_end = $ShopDetailModel -> create_at_end = $date['date'];//统计日期
        }
        $Data = $ShopDetailModel->search(Yii::$app->request->queryParams)->select('mongo_id')->asArray()->all();
        $searchModel->mongo_ids = empty($Data)?['0']:array_column($Data, 'mongo_id');
        //查询出重复签到第一次的签到id作为排除条件
        $SignCfs=SignTeamCountShopDetail::find()->where(['create_at'=>$date])->select('sign_id')->asArray()->all();
        if(!empty($SignCfs)){
            $searchModel->sign_ids=array_column($SignCfs,'sign_id');
        }

        $dataProvider = $searchModel->BusinessSearch(Yii::$app->request->queryParams);
        return $this->render('business-time-repeat-sign', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //按时间业务签到统计---重复店铺详情
    public function actionBusinessTimeRepeatShop(){
        $date = Yii::$app->request->get();
        $ShopDetailModel = new SignTeamCountShopDetailSearch();
        $searchModel = new SignSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel->create_at = $ShopDetailModel -> create_at = $date['date'];//统计日期
            $searchModel->create_at_end = $ShopDetailModel -> create_at_end = $date['date'];//统计日期
        }
        $Data = $ShopDetailModel->search(Yii::$app->request->queryParams)->select('mongo_id')->asArray()->all();
        //$searchModel->mongo_ids = array_column($Data, 'mongo_id');
        $searchModel->mongo_ids = empty($Data)?['0']:array_column($Data, 'mongo_id');
        $searchModel->RepeatShop = 1;
        $dataProvider = $searchModel->BusinessSearch(Yii::$app->request->queryParams);
        return $this->render('business-time-repeat-shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string
     * 按团队业务签到统计
     */
    public function actionBusinessTeam(){
        $searchModel = new SignTeamBusinessCountSearch();
        $search = Yii::$app->request->queryParams;
        if(empty($search['SignTeamBusinessCountSearch']['create_at']) && empty($search['SignTeamBusinessCountSearch']['create_at'])){
            $searchModel->create_at = date('Y-m-d',time()-24*3600*7);
            $searchModel->create_at_end = date('Y-m-d');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('business-team', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'create_at'=>$searchModel->create_at?$searchModel->create_at:$search['SignTeamBusinessCountSearch']['create_at'],
            'create_at_end'=>$searchModel->create_at_end?$searchModel->create_at_end:$search['SignTeamBusinessCountSearch']['create_at_end'],
        ]);
    }

    /**
     * @return string
     * 按团队业务签到统计---查看详情
     */
    public function actionBusinessTeamView(){
        $data = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $searchModel->create_at=$data['create_at'];
        $searchModel->create_at_end=$data['create_at_end'];
        $searchModel->team_id=$data['team_id'];
        $dataArr = $searchModel->BusinessTeamViewSearch(Yii::$app->request->queryParams);
        return $this->render('business-team-view', [
            'dataArr' => $dataArr,
        ]);
    }


    //按时间维护签到统计
    public function actionMaintainTime(){
        $searchModel = new SignMaintainCountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('maintain-time', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider['data'],
            'stat' => $dataProvider['stat'],
        ]);
    }

    //按时间维护签到统计---签到详情
    public function actionMaintainTimeList($date){
        $searchModel = new SignSearch();
        $searchModel -> date = $date;
        $dataProvider = $searchModel->MaintainSearch(Yii::$app->request->queryParams);
        return $this->render('maintain-time-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //按时间维护签到统计---未签到成员详情
    public function actionMaintainTimeNosign(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=2;
        $dataProvider = $searchModel->search($search);
        return $this->render('maintain-time-nosign', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //按时间维护签到统计---超时签到详情
    public function actionMaintainTimeOvertime(){
        $date = Yii::$app->request->get();
        $searchModel = new SignSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $dataProvider = $searchModel->MaintainOvertimeSearch($search);
        return $this->render('maintain-time-overtime', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    //按时间维护签到统计---未达标成员详情
    public function actionMaintainTimeUnqualified(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=2;
        $dataProvider = $searchModel->unqualifiedsearch($search);
        return $this->render('maintain-time-unqualified', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //按时间维护签到统计---早退成员详情
    public function actionMaintainTimeLeaveEarly(){
        $date = Yii::$app->request->get();
        $searchModel = new SignMemberCountSearch();
        $search = Yii::$app->request->queryParams;
        if(!empty($date['date']) && empty($search['create_at']) && empty($search['create_at_end'])){
            $searchModel -> create_at = $date['date'];//统计日期
            $searchModel -> create_at_end = $date['date'];//统计日期
        }
        $searchModel->team_type=2;
        $dataProvider = $searchModel->unqualifiedsearch($search);
        return $this->render('maintain-time-leave-early', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * 按团队维护签到统计
     */
    public function actionMaintainTeam(){
        $searchModel = new SignTeamMaintainCountSearch();
        $search = Yii::$app->request->queryParams;
        if(empty($search['SignTeamMaintainCountSearch']['create_at']) && empty($search['SignTeamMaintainCountSearch']['create_at'])){
            $searchModel->create_at = date('Y-m-d',time()-24*3600*7);
            $searchModel->create_at_end = date('Y-m-d');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('maintain-team', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'create_at'=>$searchModel->create_at?$searchModel->create_at:$search['SignTeamMaintainCountSearch']['create_at'],
            'create_at_end'=>$searchModel->create_at_end?$searchModel->create_at_end:$search['SignTeamMaintainCountSearch']['create_at_end'],
        ]);
    }

    /**
     * @return string
     * 按团队维护签到统计---查看详情
     */
    public function actionMaintainTeamView(){
        $data = Yii::$app->request->get();

        $searchModel = new SignMemberCountSearch();
        $searchModel->create_at=$data['create_at'];
        $searchModel->create_at_end=$data['create_at_end'];
        $searchModel->team_id=$data['team_id'];
        $dataArr = $searchModel->MaintainTeamViewSearch(Yii::$app->request->queryParams);
        //      ToolsClass::p($dataArr);die;
        return $this->render('maintain-team-view', [
            'dataArr' => $dataArr,
        ]);
    }

    /**
     * @return string
     * 团队管理日志
     */
    public function actionSignLog(){
        $searchModel = new SignLogSearch();
        $dataProvider = $searchModel->Search(Yii::$app->request->queryParams);
        return $this->render('sign-log', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    //业务签到基础设置
    public function actionSalesmanSignSet(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $configModel->load(Yii::$app->request->post());
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('salesman-sign');
        }
        return $this->render('salesman-sign-set',[
            'model' => $configModel,
        ]);
    }
    
    // 维护签到基础设置
    public function actionMaintainSignSet(){
        $configModel = new SystemConfig();
        if(Yii::$app->request->isPost){
            $configModel->load(Yii::$app->request->post());
            $configModel->saveConfig();
        }else{
            $configModel->loadConfigData('maintain');
        }
        return $this->render('maintain-sign-set',[
            'model' => $configModel,
        ]);
    }


    /**
     * Finds the SignTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SignTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SignTeam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
