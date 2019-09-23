<?php
namespace cms\modules\examine\controllers;

use cms\models\LogExamine;
use cms\modules\config\models\User;
use cms\modules\member\models\MemberInfo;
use cms\modules\shop\models\Shop;
use Yii;
use cms\modules\shop\models\BuildingShopFloor;
use cms\modules\shop\models\search\BuildingShopFloorSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\core\CmsController;
use cms\modules\examine\models\search\MemberInfoSearch;


/**
 * BuildingShopFloorController implements the CRUD actions for BuildingShopFloor model.
 */
class ExamineFloorController extends CmsController
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
     * Lists all BuildingShopFloor models.
     * Led显示类表
     * @return mixed
     */
    public function actionLedIndex()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('led-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //认领led
    public function actionFloorClaimLed()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 1;
        $searchModel->default_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('floor-claim-led', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //商家认领确认
    public function actionFloorConfirmClaim(){
        $data=Yii::$app->request->post();
        $member_group=  Yii::$app->user->identity->member_group;
        if($member_group != 0) {
            $UserArr = User::find()->where(['member_group' => $member_group])->asArray()->all();
        }
        if(count($UserArr)==2){
            foreach($UserArr as $v){
                $username[]=$v['username'];
            }
        }else{
            return json_encode(['code'=>3,'msg'=>'认领组别人数不对！']);
        }

        if($data['type'] == 'led'){
            $res = BuildingShopFloor::updateAll(['led_examine_user_group'=>$member_group,'led_examine_user_name'=>implode(',',$username)],['id'=>$data['id'],'led_examine_user_group'=>'']);
        }else if ($data['type'] == 'poster'){
            $res = BuildingShopFloor::updateAll(['poster_examine_user_group'=>$member_group,'poster_examine_user_name'=>implode(',',$username)],['id'=>$data['id'],'poster_examine_user_group'=>'']);
        }

        if($res){
            return json_encode(['code'=>1,'msg'=>'认领成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'已被认领']);
        }
    }

    /**
     * Lists all BuildingShopFloor models.
     * 画框显示类表
     * @return mixed
     */
    public function actionPosterIndex()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 4;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('poster-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //认领画报
    public function actionFloorClaimPoster()
    {
        $searchModel = new BuildingShopFloorSearch();
        $searchModel->type = 2;
        $searchModel->default_status = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('floor-claim-poster', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BuildingShopFloor model.
     * @param string $id,$type
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$type)
    {
        if($type ==1){
            return $this->render('led-view', [
                'model' => $this->findModel($id),
                'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>10])->orderBy('create_at desc')->asArray()->all()
            ]);
        }else{
            return $this->render('poster-view', [
                'model' => $this->findModel($id),
                'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>11])->orderBy('create_at desc')->asArray()->all()
            ]);
        }

    }

    public function actionInstallLedView($id){
        
        return $this->render('install-led-view', [
        ]);
    }


    /**
     * Creates a new BuildingShopFloor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildingShopFloor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BuildingShopFloor model.
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
     * Deletes an existing BuildingShopFloor model.
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
     * Finds the BuildingShopFloor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BuildingShopFloor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BuildingShopFloor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //楼宇审核审核
    public function actionFloorExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['shop_id']){
            return false;
        }
        $shopModel = BuildingShopFloor::findOne(['id'=>$arr['shop_id']]);
        if($shopModel){
            //是否已经审核过
            if($arr['device_type'] == 'led'){
                if($shopModel->led_examine_status > 0){
                    return 5;
                }
            }elseif($arr['device_type'] == 'poster'){
                if($shopModel->poster_examine_status > 0){
                    return 5;
                }
            }

            $status = $arr['type'] == 'pass' ? 2 : 1;
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = BuildingShopFloor::examineFloor($shopModel,$arr['device_type'],$status,$desc);
            if($res == 1 || $res == 2){
                return $res;
            }else{
                return $res =0;
            }
        }else{
            return false;
        }
    }

    // 审核人员指派列表
    public function actionAuditAssignList($id,$screen_type){
        $searchModel = new MemberInfoSearch();
        $searchModel->auditassign=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('audit-assign', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
            'screen_type'=>$screen_type,
        ]);
    }

    // 审核人员指派
    public function actionAuditAssign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $idAll=explode(',',$data['id']);
            $wait_shop_number=  count($idAll);  //待安装店铺数量
            $ShopDate=BuildingShopFloor::find()->where(['in','id',$idAll])->select('id,led_install_member_id,poster_install_member_id,')->asArray()->all();
            foreach ($ShopDate as $v){
                if($v['led_install_member_id']!=0 && $data['screen_type'] == 'led'){
                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺'.$v['id'].'已指派']);
                }
                if($v['poster_install_member_id']!=0 && $data['screen_type'] == 'poster'){
                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺'.$v['id'].'已指派']);
                }
            }
            if($data['screen_type'] == 'led'){
                $wait_screen_number= BuildingShopFloor::find()->where(['in','id',$idAll])->sum('led_total_screen_number');//待安装屏幕数量
                BuildingShopFloor::updateAll(['led_install_member_id'=>$data['member_id'],'led_install_member_name'=>$data['name'],'led_install_mobile'=>$data['mobile'],'led_install_assign_at'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
                MemberInfo::updateAllCounters(['wait_screen_number'=>$wait_screen_number,'wait_shop_number'=>$wait_shop_number],['member_id'=>$data['member_id']]);
            }elseif ($data['screen_type'] == 'poster'){
                $wait_screen_number= BuildingShopFloor::find()->where(['in','id',$idAll])->sum('poster_total_screen_number');//待安装海报数量
                BuildingShopFloor::updateAll(['poster_install_member_id'=>$data['member_id'],'poster_install_member_name'=>$data['name'],'poster_install_mobile'=>$data['mobile'],'poster_install_assign_at'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
                MemberInfo::updateAllCounters(['wait_screen_number'=>$wait_screen_number,'wait_shop_number'=>$wait_shop_number],['member_id'=>$data['member_id']]);
            }
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    //审核人员取消指派
    public function actionNoAuditAssign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model=BuildingShopFloor::findOne(['id'=>$data['id']]);
            if($data['screen_type'] == 'led'){
                $MemberModel=MemberInfo::findOne(['member_id'=>$model->led_install_member_id]);
                $MemberModel->wait_shop_number-=1;//待安装的店铺数量
                $MemberModel->wait_screen_number-=$model->led_total_screen_number;//待安装的屏幕数量
                $MemberModel->save();

                $model->led_install_member_id=0;
                $model->led_install_member_name='';
                $model->led_install_mobile='';
                $model->led_install_assign_at='0000-00-00 00:00:00';
                $model->save();
            }elseif($data['screen_type'] == 'poster'){
                $MemberModel=MemberInfo::findOne(['member_id'=>$model->poster_install_member_id]);
                $MemberModel->wait_shop_number-=1;//待安装的店铺数量
                $MemberModel->wait_screen_number-=$model->poster_total_screen_number;//待安装的屏幕数量
                $MemberModel->save();

                $model->poster_install_member_id=0;
                $model->poster_install_member_name='';
                $model->poster_install_mobile='';
                $model->poster_install_assign_at='0000-00-00 00:00:00';
                $model->save();
            }

            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'取消成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }
}
