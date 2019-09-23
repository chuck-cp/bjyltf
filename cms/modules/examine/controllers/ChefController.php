<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 20:39
 */

namespace cms\modules\examine\controllers;
use cms\modules\examine\models\search\MemberInfoSearch;
use common\libs\ToolsClass;
use Yii;
use cms\modules\member\models\Member;
use cms\modules\member\models\search\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\SystemAddress;
use cms\modules\member\models\search\MemberLowerSearch;
use cms\modules\shop\models\search\ShopSearch;
use cms\modules\member\models\MemberBank;
use cms\modules\member\models\search\MemberBankSearch;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberLower;
use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\member\models\MemberTeamList;
use cms\modules\member\models\MemberTeam;
class ChefController extends CmsController
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 地区切换
     */
    public function actionAddress(){
        $parent_id = Yii::$app->request->post('parent_id');
        if(!$parent_id){
            return [];
        }
        $adrsModel = new SystemAddress();
        return json_encode($adrsModel::getAreasByPid($parent_id),true);
    }
    /**
     * Displays a single Member model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        //驳回原因
        $examine=LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>'2'])->orderBy('create_at desc')->asArray()->all();

        $memberinfo = MemberInfo::findOne(['member_id'=>$id]);
        $membermodel = Member::findOne(['id'=>$id]);
        return $this->render('view', [
            'examine'=>$examine,
//            'model' => $this->findModel($id),
            'model' => $memberinfo,
            'membermodel' => $membermodel,
        ]);
    }
    /**
     * 身份证审核
     */
    public function actionExamineCard(){
        $arr = Yii::$app->request->post();
        $model = MemberInfo::findOne(['member_id'=>$arr['member_id']]);
        //如果已经审核过则直接返回
        if($model->examine_status == 1 || $model->examine_status == 2){
            return 5;
        }
        if($model->examine_status == -1){
            return 4;
        }
        if($model){
            $status = $arr['type'] == 'pass' ? 1 : 2;
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = MemberInfo::saveInfo($model, $status, $desc);
            return $res == true ? 1 : 0;
        }else{
            return 0;
        }
    }
    /**
     * 伙伴信息
     */
    public function actionPartner($id){
        $searchModel = new MemberSearch();
        $searchModel->parent_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('partner',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 商家信息
     */
    public function actionShop($id){
        $searchModel = new ShopSearch();
        $searchModel->member_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('shop',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 我的LED信息
     */
    public function actionLed(){
        $searchModel = new ShopSearch();
        $id = Yii::$app->request->get('id');
        $searchModel->member_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('led',[
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * 安装人员审核列表
     */
    public function actionInstaller()
    {
        $searchModel = new MemberInfoSearch();
        $searchModel->installer_status=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('installer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 设置是否为内部电工
     */
    public function actionIsitofficial(){
        $data=Yii::$app->request->post();
        $member = MemberInfo::findOne(['member_id'=>$data['id']]);
        if($member->electrician_examine_status <> 1){
            return json_encode(['code'=>3,'msg'=>'电工审核未通过，无法设置！']);
        }
        if(!empty($data)){
            if($data['status']==1){
                $status=2;
            }else if($data['status']==2){
                $status=1;
            }
            if(MemberInfo::updateAll(['company_electrician'=>$status],['member_id'=>$data['id']])){
                return json_encode(['code'=>1,'msg'=>'完成']);
            }else{
                return json_encode(['code'=>2,'msg'=>'失败']);
            }
        }
    }

    /**
     * 安装人员详细信息
     */
    public function actionInstallerView($id){
        $model=MemberInfo::findOne(['member_id'=>$id]);
        if((int)$model->join_team_id==0){
            $MemberTeamModel=[];
        }else{
            $temomodel=MemberTeamList::findOne(['member_id'=>$id]);
            $MemberTeamModel=MemberTeam::findOne(['id'=>$temomodel->team_id]);
        }
        return $this->render('installer-view',[
            'model'=>$model,
            'MemberTeamModel'=>$MemberTeamModel,
            'rejectAll'=>LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>6])->orderBy('create_at desc')->asArray()->all()
        ]);
    }

    /**
     * 安装人员审核
     */
    public function actionInstallerExamine()
    {
        $DataArr = Yii::$app->request->post();
        if(empty($DataArr)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        try{
            $transaction = Yii::$app->db->beginTransaction();
            $LogExamine=new LogExamine();
            $LogExamine->examine_key=6;
            $LogExamine->foreign_id=$DataArr['member_id'];
            $DataArr['type']==1?$LogExamine->examine_result=1:$LogExamine->examine_result=2;
            $DataArr['type']==2?$LogExamine->examine_desc=$DataArr['desc']:'审核通过';
            $LogExamine->create_user_id=Yii::$app->user->identity->getId();
            $LogExamine->create_user_name=Yii::$app->user->identity->username;
            $LogExamine->create_at=date('Y-m-d H:i:s');
            $LogExamine->save();
            MemberInfo::updateAll(['electrician_examine_status'=>$DataArr['type']==1?1:2],['member_id'=>$DataArr['member_id']]);
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>1,'msg'=>'操作失败']);
        }
    }



    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}