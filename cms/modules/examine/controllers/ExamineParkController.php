<?php

namespace cms\modules\examine\controllers;

use cms\core\CmsController;
use cms\models\LogExamine;
use cms\modules\config\models\User;
use cms\modules\examine\models\search\MemberInfoSearch;
use cms\modules\member\models\MemberInfo;
use Yii;
use cms\modules\shop\models\BuildingShopPark;
use cms\modules\shop\models\search\BuildingShopParkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\modules\shop\models\BuildingShopPositionDifferent;
use common\libs\ToolsClass;

/**
 * BuildingShopParkController implements the CRUD actions for BuildingShopPark model.
 */
class ExamineParkController extends CmsController
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
     * Lists all BuildingShopPark models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BuildingShopParkSearch();
        $searchModel->zhuangtai = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //认领
    public function actionParkClaim()
    {
        $searchModel = new BuildingShopParkSearch();
        $searchModel->default_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('park-claim', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //商家认领确认
    public function actionPorkConfirmClaim(){
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
        $res = BuildingShopPark::updateAll(['poster_examine_user_group'=>$member_group,'poster_examine_user_name'=>implode(',',$username)],['id'=>$data['id'],'poster_examine_user_group'=>'']);
        if($res){
            return json_encode(['code'=>1,'msg'=>'认领成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'已被认领']);
        }
    }

    /**
     * Displays a single BuildingShopPark model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $InstallDatas = BuildingShopPositionDifferent::getDifferentDatas($id);
        return $this->render('view', [
            'InstallDatas'=>$InstallDatas,
            'model' => $this->findModel($id),
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>12])->orderBy('create_at desc')->asArray()->all()
        ]);
    }

    /**
     * Creates a new BuildingShopPark model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildingShopPark();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BuildingShopPark model.
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
     * Deletes an existing BuildingShopPark model.
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
     * Finds the BuildingShopPark model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BuildingShopPark the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BuildingShopPark::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //审核
    public function actionParkExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['shop_id']){
            return false;
        }
        $shopModel = BuildingShopPark::findOne(['id'=>$arr['shop_id']]);
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
            $res = BuildingShopPark::examinePark($shopModel,$arr['device_type'],$status,$desc);
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
            $ShopDate=BuildingShopPark::find()->where(['in','id',$idAll])->select('id,poster_install_member_id,')->asArray()->all();//led_install_member_id
            foreach ($ShopDate as $v){
//                if($v['led_install_member_id']!=0 && $data['screen_type'] == 'led'){
//                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺'.$v['id'].'已指派']);
//                }
                if($v['poster_install_member_id']!=0 && $data['screen_type'] == 'poster'){
                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺'.$v['id'].'已指派']);
                }
            }
            if($data['screen_type'] == 'led'){
                $wait_screen_number= BuildingShopPark::find()->where(['in','id',$idAll])->sum('led_total_screen_number');//待安装屏幕数量
                BuildingShopPark::updateAll(['led_install_member_id'=>$data['member_id'],'led_install_member_name'=>$data['name'],'led_install_mobile'=>$data['mobile'],'led_install_assign_at'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
                MemberInfo::updateAllCounters(['wait_screen_number'=>$wait_screen_number,'wait_shop_number'=>$wait_shop_number],['member_id'=>$data['member_id']]);
            }elseif ($data['screen_type'] == 'poster'){
                $wait_screen_number= BuildingShopPark::find()->where(['in','id',$idAll])->sum('poster_total_screen_number');//待安装海报数量
                BuildingShopPark::updateAll(['poster_install_member_id'=>$data['member_id'],'poster_install_member_name'=>$data['name'],'poster_install_mobile'=>$data['mobile'],'poster_install_assign_at'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
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
            $model=BuildingShopPark::findOne(['id'=>$data['id']]);
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
