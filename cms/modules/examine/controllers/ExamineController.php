<?php

namespace cms\modules\examine\controllers;
use cms\modules\examine\models\ShopHeadquartersList;
use cms\modules\member\models\MemberInfo;
use cms\modules\member\models\MemberTeam;
use common\libs\ToolsClass;
use Yii;
use cms\modules\shop\models\Shop;
use cms\modules\shop\models\search\ShopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cms\models\SystemAddress;
use cms\core\CmsController;
use cms\modules\examine\models\search\MemberInfoSearch;
use cms\models\LogExamine;
use cms\modules\authority\models\User;
use cms\modules\member\models\search\MemberTeamSearch;
use cms\modules\member\models\MemberTeamList;
use cms\modules\shop\models\ShopLable;
/**
 * ExamineController implements the CRUD actions for Shop model.
 */
class ExamineController extends CmsController
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
     */
    public function actionIndex(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 2;
        $searchModel->install_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //商家审核内部
    public function actionOfflineShop(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 4;
        $searchModel->install_status = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('offline-shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single shop model.
     */
    public function actionView($id)
    {
        $apply_name = Yii::$app->request->get('apply_name');
        $model = $this->findModel($id);
        $headlistModel = ShopHeadquartersList::findOne(['id' => $model->headquarters_list_id,'headquarters_id' => $model->headquarters_id]);//连锁分店ID绑定的shopid
        if(!empty($headlistModel->shop_id)){
            $headlistshopid = $headlistModel->shop_id;
        }else{
            $headlistshopid = 0;
        }
        return $this->render('view', [
            'model' => $model,
            'headlistShopid' => $headlistshopid,
            'apply_name' =>  $apply_name ? $apply_name : '---',
            'desc' => LogExamine::find()->where(['foreign_id'=>$id,'examine_key'=>1])->orderBy('create_at desc')->asArray()->all()
        ]);
    }
    /**
     *商家审核
     */
    public function actionExamine(){
        $arr = Yii::$app->request->post();
        if(!$arr['shop_id']){
            return false;
        }
        $shopModel = Shop::findOne(['id'=>$arr['shop_id']]);
        if($shopModel){
            //是否已经审核过
            if($shopModel->status > 0){
                return 5;
            }
            $status = $arr['type'] == 'pass' ? 2 : 1;
            $desc = isset($arr['desc']) ? $arr['desc'] : '0';
            $res = Shop::examineShop($shopModel,$status,$desc);
            if($res == 1 || $res == 2){
                return $res;
            }else{
                return $res =0;
            }
        }else{
            return false;
        }

    }
    /**
     * 修改镜面数量
     */
    public function actionModifyScreen(){
        $arr = Yii::$app->request->post();
        if(!$arr['shop_id'] || !is_int(intval($arr['num']))){
            return 0;
        }
        $model = Shop::findOne(['id'=>$arr['shop_id']]);
        $model->screen_number = intval($arr['num']);
        $re = $model->save(false);
        return $re;
    }

    /**
     * 商家认领
     */
    public function actionClaim(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 5;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('claim', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 商家认领确认
     */
    public function actionConfirmClaim(){
       $data=Yii::$app->request->post();
       $member_group=  Yii::$app->user->identity->member_group;
       $UserArr=User::find()->where(['member_group'=>$member_group])->asArray()->all();
       foreach($UserArr as $v){
           $username[]=$v['username'];
       }
       if(Shop::updateAll(['examine_user_group'=>$member_group,'examine_user_name'=>implode(',',$username)],['id'=>$data['id'],'examine_user_group'=>''])){
           return json_encode(['code'=>1,'msg'=>'认领成功']);
       }else{
           return json_encode(['code'=>2,'msg'=>'已被认领']);
       }

    }

    /**
     * 指派安装人列表
     */
    public function actionInstallerAssign(){
        $searchModel = new ShopSearch();
        $searchModel->default_status = 3;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('installer-assign', [
            'LableArr'=>ShopLable::find()->asArray()->all(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 指派个人
     */
    public function actionAssignPersonal($id){
        $searchModel = new MemberInfoSearch();
        $searchModel->electrician_status=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('assign-personal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }
    /**
     * 指派小组
     */
    public function actionAssignGroup($id){
        $searchModel = new MemberTeamSearch();
        $searchModel->groupstatus=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('assign-group', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }

    /**
     * 指派
     */
    public function actionAssign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $idAll=explode(',',$data['id']);
            $wait_shop_number=  count($idAll);  //待安装店铺数量
            $ShopDate=Shop::find()->where(['in','id',$idAll])->select('id,install_team_id,install_member_id,')->asArray()->all();
            foreach ($ShopDate as $v){
                if($v['install_team_id']!=0 || $v['install_member_id']!=0){
                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺已指派']);
                }
            }
            if($data['type']==1){
                $wait_screen_number= shop::find()->where(['in','id',$idAll])->sum('screen_number');//待安装屏幕数量
                Shop::updateAll(['install_member_id'=>$data['member_id'],'install_member_name'=>$data['name'],'install_mobile'=>$data['mobile'],'install_assign_at'=>date('Y-m-d'),'install_assign_time'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
                MemberInfo::updateAllCounters(['wait_screen_number'=>$wait_screen_number,'wait_shop_number'=>$wait_shop_number],['member_id'=>$data['member_id']]);
            }else{
                Shop::updateAll(['install_team_id'=>$data['team_id'],'install_assign_at'=>date('Y-m-d'),'install_assign_time'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
                MemberTeam::updateAllCounters(['not_assign_shop_number'=>$wait_shop_number], ['id' => $data['team_id']]);
            }//
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    /**
     * 取消指派
     */
    public function actionNoAssign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model=shop::findOne(['id'=>$data['id']]);
            //判断是指派给个人的时候
            if($model->install_team_id==0){
                $MemberModel=MemberInfo::findOne(['member_id'=>$model->install_member_id]);
                $MemberModel->wait_shop_number-=1;//待安装的店铺数量
                $MemberModel->wait_screen_number-=$model->screen_number;//待安装的屏幕数量
                $MemberModel->save();
            }else{//判断是指派给小组的时候
                //该小组还未指派给小组成员
                $TeamModel=MemberTeam::findOne(['id'=>$model->install_team_id]);
                if($model->install_member_id==0){
                    $TeamModel->not_assign_shop_number-=1;
                    $TeamModel->save();
                }else{//该小组指派给小组成员
                    $TeamListModel=MemberTeamList::findOne(['member_id'=>$model->install_member_id,'team_id'=>$TeamModel->id,'status'=>1]);
                    $TeamModel->not_install_shop_number-=1;
                    $TeamModel->not_assign_shop_number-=1;
                    $TeamModel->save();
                    if($TeamListModel){
                        $TeamListModel->wait_shop_number-=1;
                        $TeamListModel->wait_screen_number-=$model->screen_number;
                        $TeamListModel->save();
                    }
                }
            }
            $model->install_member_id=0;
            $model->install_member_name='';
            $model->install_team_id=0;
            $model->install_mobile='';
            $model->install_assign_at='0000-00-00';
            $model->save();
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'取消成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    /**
     * 审核人员指派列表
     */
    public function actionAuditAssignList($id){
        $searchModel = new MemberInfoSearch();
        $searchModel->auditassign=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('audit-assign', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id'=>$id,
        ]);
    }

    /**
     * 审核人员指派
     */
    public function actionAuditAssign(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            return json_encode(['code'=>0,'msg'=>'非法数据']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $idAll=explode(',',$data['id']);
            $wait_shop_number=  count($idAll);  //待安装店铺数量
            $ShopDate=Shop::find()->where(['in','id',$idAll])->select('id,install_team_id,install_member_id,')->asArray()->all();
            foreach ($ShopDate as $v){
                if($v['install_team_id']!=0 || $v['install_member_id']!=0){
                    return json_encode(['code'=>3,'msg'=>'操作失败,店铺已指派']);
                }
            }
            $wait_screen_number= shop::find()->where(['in','id',$idAll])->sum('screen_number');//待安装屏幕数量
            Shop::updateAll(['install_member_id'=>$data['member_id'],'install_member_name'=>$data['name'],'install_mobile'=>$data['mobile'],'install_assign_at'=>date('Y-m-d'),'install_assign_time'=>date('Y-m-d H:i:s')],['in','id',$idAll]);
            MemberInfo::updateAllCounters(['wait_screen_number'=>$wait_screen_number,'wait_shop_number'=>$wait_shop_number],['member_id'=>$data['member_id']]);
            $transaction->commit();
            return json_encode(['code'=>1,'msg'=>'操作成功']);
        }catch (Exception $e){
            Yii::error($e->getMessage(),'error');
            $transaction->rollBack();
            return json_encode(['code'=>2,'msg'=>'操作失败']);
        }
    }

    /**
     * 审核人员取消指派
     */
     public function actionNoAuditAssign(){
         $data=Yii::$app->request->post();
         if(empty($data)){
             return json_encode(['code'=>0,'msg'=>'非法数据']);
         }
         $transaction = Yii::$app->db->beginTransaction();
         try{
             $model=shop::findOne(['id'=>$data['id']]);
             //判断是指派给个人的时候
             if($model->install_team_id==0){
                 $MemberModel=MemberInfo::findOne(['member_id'=>$model->install_member_id]);
                 $MemberModel->wait_shop_number-=1;//待安装的店铺数量
                 $MemberModel->wait_screen_number-=$model->screen_number;//待安装的屏幕数量
                 $MemberModel->save();
             }
             $model->install_member_id=0;
             $model->install_member_name='';
             $model->install_team_id=0;
             $model->install_mobile='';
             $model->install_assign_at='0000-00-00';
             $model->save();
             $transaction->commit();
             return json_encode(['code'=>1,'msg'=>'取消成功']);
         }catch (Exception $e){
             Yii::error($e->getMessage(),'error');
             $transaction->rollBack();
             return json_encode(['code'=>2,'msg'=>'操作失败']);
         }
     }
    /**
     * Finds the shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     */
    protected function findModel($id)
    {
        if (($model = shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
